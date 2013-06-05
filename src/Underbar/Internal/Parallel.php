<?php

namespace Underbar\Internal;

class Parallel implements \Iterator, \Countable
{
    /**
     * Sockets for interprocess communication
     *
     * @var  array
     */
    protected $sockets = array();

    /**
     * The queue of processing data
     *
     * @var  SplQueue
     */
    protected $queue;

    /**
     * Processing results
     *
     * @var  SplQueue
     */
    protected $results;

    /**
     * The remaining number of tasks
     *
     * @var  int
     */
    protected $remain = 0;

    /**
     * The procedure to execute in child processes
     *
     * @var  callable
     */
    protected $procedure;

    /**
     * The timeout of a IO wait
     *
     * @var  int
     */
    protected $timeout;

    /**
     * Get the number of processors.
     *
     * @return  int
     */
    protected static function processors()
    {
        static $processors;

        if (isset($processors)) {
            return $processors;
        }

        switch (PHP_OS) {
            case 'Linux':
                $cmd = 'cat /proc/cpuinfo | grep processor | wc -l';
                break;
            case 'BSD':
            case 'FreeBSD':
            case 'NetBSD':
            case 'OpenBSD':
                $cmd = "sysctl -a | grep 'hw.ncpu:' | cut -d ':' -f2";
                break;
            case 'Darwin':
                $cmd = "/usr/sbin/sysctl -a | grep 'hw.ncpu:' | cut -d ':' -f2";
                break;
            default:
                return $processors = 4;
        }

        return $processors = (int) trim(shell_exec($cmd));
    }

    /**
     * Send the signal.
     *
     * @param   int   $pid     Process ID
     * @param   int   $signal  Signal to sent
     * @return  bool
     */
    protected static function signal($pid, $signal)
    {
        if (function_exists('posix_kill')) {
            return posix_kill($pid, $signal);
        } else {
            system("kill -$signal $pid", $result);
            return $result === 0;
        }
    }

    /**
     * Return the socket pair for interprocess communication.
     *
     * @return  array
     */
    protected static function pair()
    {
        return stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
    }

    /**
     * Read data with unserialize from a socket.
     *
     * @param   resource  $socket
     * @return  void
     * @throws  RuntimeException
     */
    protected static function read($socket)
    {
        $result = fgets($socket);
        if ($result === false) {
            throw new \RuntimeException();
        }
        return static::unserialize($result);
    }

    /**
     * Write data with serialize to a socket.
     *
     * @param   resource  $socket
     * @param   mixed     $value
     * @return  bool
     */
    protected static function write($socket, $value)
    {
        $value = static::serialize($value) . PHP_EOL;
        $written = 0;
        while ($written < strlen($value)) {
            $bytes = fwrite($socket, substr($value, $written));
            if ($bytes === false) {
                return false;
            }
            $written += $bytes;
        }
        return true;
    }

    /**
     * Serialize data
     *
     * @param   mixed  $source
     * @return  string
     */
    protected static function serialize($source)
    {
        return base64_encode(serialize($source));
    }

    /**
     * Unserialize data
     *
     * @param   mixed   $source
     * @return  string
     * @throws  UnexpectedValueException
     */
    protected static function unserialize($source)
    {
        $source = base64_decode($source);
        $result = @unserialize($source);
        if ($result === false && $source !== serialize(false)) {
            throw new \UnexpectedValueException();
        }
        return $result;
    }

    /**
     * Forked processes and start tasks.
     *
     * @param  callable  $procedure  Procedure to execute in child processes
     * @param  int       $n          Number of process
     * @param  int       $timeout    Timeout of a IO wait
     */
    public function __construct($procedure, $n = null, $timeout = null)
    {
        $this->queue = new \SplQueue();
        $this->results = new \SplQueue();
        $this->procedure = $procedure;
        $this->timeout = $timeout;

        for ($n = $n > 0 ? $n : static::processors(); $n-- > 0;) {
            $this->fork();
        }
    }

    /**
     * Close sockets and kill workers.
     */
    public function __destruct()
    {
        foreach ($this->sockets as $pid => $socket) {
            fclose($socket);
            static::signal($pid, SIGTERM);
        }
    }

    /**
     * Start a worker process.
     *
     * @return  int  Forked process ID
     */
    public function fork()
    {
        $pair = $this->pair();

        $pid = pcntl_fork();
        if ($pid < 0) {  // Failed fork
            throw new \RuntimeException('Failed to fork child process.');
        } elseif ($pid === 0) {  // is worker process
            fclose($pair[0]);
            foreach ($this->sockets as $socket) {
                fclose($socket);
            }
            $this->sockets = array();
            $this->loop($pair[1]);
            exit;
        }

        fclose($pair[1]);
        $this->sockets[$pid] = $pair[0];

        return $pid;
    }

    /**
     * Stop a worker process.
     *
     * @return  int  A process ID to be stopped
     */
    public function terminate()
    {
        if ($socket = end($this->sockets)) {
            // Quit the worker
            fwrite($socket, PHP_EOL);
        }

        // Return the process ID
        return key($this->sockets);
    }

    /**
     * Push a value to the queue.
     *
     * @param   mixed  $value  A pushed value
     * @return  void
     */
    public function push($value)
    {
        $this->queue->enqueue($value);
        $this->flush();
    }

    /**
     * Push all values to the queue.
     *
     * @param   array|Traversable  $values  Array of the pushed value
     * @return  void
     */
    public function pushAll($values)
    {
        foreach ($values as $value) {
            $this->queue->enqueue($value);
        }
        for ($i = count($this->queue); $i-- && !$this->queue->isEmpty();) {
            $this->flush();
        }
    }

    /**
     * Return a result.
     *
     * @return  mixed
     */
    public function result()
    {
        $this->fill();
        return $this->results->isEmpty() ? null : $this->results->dequeue();
    }

    /**
     * Return a current result.
     *
     * @see     Iterator
     * @return  mixed
     */
    public function current()
    {
        return $this->results->bottom();
    }

    /**
     * Not implemented.
     *
     * @see     Iterator
     * @return  void
     */
    public function key()
    {
    }

    /**
     * @see     Iterator
     * @return  void
     */
    public function next()
    {
        $this->results->dequeue();
        $this->fill();
    }

    /**
     * @see     Iterator
     * @return  void
     */
    public function rewind()
    {
        $this->fill();
    }

    /**
     * @see     Iterator
     * @return  bool
     */
    public function valid()
    {
        return !$this->results->isEmpty();
    }

    /**
     * Remaining number of tasks.
     *
     * @see     Countable
     * @return  int
     */
    public function count()
    {
        return $this->remain;
    }

    /**
     * Read process results.
     * Block when results are empty.
     *
     * @return  void
     */
    protected function fill()
    {
        if (count($this) <= 0) {
            return;
        }

        $read = $this->sockets;
        $write = null;
        $except = null;
        $time = count($this->results) > 0 ? 0 : $this->timeout;

        if (stream_select($read, $write, $except, $time) > 0) {
            foreach ($read as $socket) {
                try {
                    $result = static::read($socket);
                } catch (\RuntimeException $e) {
                    continue;
                }
                $this->results->enqueue($result);
                $this->remain--;
            }
        }
    }

    /**
     * Write queueing values (might block).
     *
     * @return  void
     */
    protected function flush()
    {
        if (empty($this->sockets) && $this->fork() <= 0) {
            // Failed fork
            return;
        }

        $read = null;
        $write = $this->sockets;
        $except = null;

        if (stream_select($read, $write, $except, $this->timeout) > 0) {
            foreach ($write as $socket) {
                if ($this->queue->isEmpty()) {
                    break;
                }
                if (static::write($socket, $this->queue->dequeue()) !== false) {
                    $this->remain++;
                }
            }
        }
    }

    /**
     * The loop for worker processes to processing a task.
     *
     * @param   resource  $socket
     * @return  void
     */
    protected function loop($socket)
    {
        while (true) {
            try {
                $result = static::read($socket);  // is blocking
            } catch (\RuntimeException $e) {
                break;
            }
            static::write($socket, call_user_func($this->procedure, $result));
        }
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
