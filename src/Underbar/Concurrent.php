<?php

namespace Underbar;

class Concurrent implements \Iterator, \Countable
{
    use Enumerable;

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
     * @return  mixed
     */
    protected static function read($socket)
    {
        if (($result = fgets($socket)) !== false) {
            $result = unserialize($result);
        }

        return $result;
    }

    /**
     * Write data with serialize to a socket.
     *
     * @param   resource  $socket
     * @return  mixed
     */
    protected static function write($socket, $value)
    {
        return fwrite($socket, serialize($value) . PHP_EOL);
    }

    /**
     * Forked processes and start tasks.
     *
     * @param  callable  $procedure  Procedure to execute in child processes
     * @param  int       $n          Number of process
     * @param  int       $timeout    Timeout of a IO wait
     */
    public function __construct($procedure, $n = 1, $timeout = null)
    {
        $this->queue = new \SplQueue();
        $this->results = new \SplQueue();
        $this->procedure = $procedure;
        $this->timeout = $timeout;

        while ($n-- > 0) {
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
            if ($pid > 0) {
                static::signal($pid, SIGTERM);
            }
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
        if ($pid < 0) {
            // Failed fork
            exit(1);
        } elseif ($pid === 0) {
            // is worker process
            fclose($pair[0]);
            foreach ($this->sockets as $socket) {
                fclose($socket);
            }

            $this->sockets = array($pair[1]);
            pcntl_signal(SIGCHLD, array($this, 'handler'));

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
     * @return  int  Terminated process ID
     */
    public function terminate()
    {
        if (($pid = key($this->sockets)) !== null) {
            static::signal($pid, SIGCHLD);
            pcntl_waitpid($pid, $status);

            $socket = $this->sockets[$pid];
            while (!feof($socket) && $this->remain--) {
                if (($result = static::read($socket)) !== false) {
                    $this->results->enqueue($result);
                }
            }

            fclose($socket);
            unset($this->sockets[$pid]);
        }

        return $pid;
    }

    /**
     * Push a value to the queue.
     *
     * @param   mixed  $value  The pushed value
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
        for ($i = count($this->queue); $i-- && count($this->queue); $this->flush());
    }

    /**
     * Return only one result.
     *
     * @return  mixed
     */
    public function result()
    {
        $this->fill();
        return $this->results->dequeue();
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
        // Not implemented
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
     * @return  boolean
     */
    public function valid()
    {
        return count($this->results);
    }

    /**
     * Remaining number of tasks
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
     * Block when result is empty.
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
                if (($result = static::read($socket)) !== false) {
                    $this->results->enqueue($result);
                }
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
                if (count($this->queue) === 0) {
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
        for (;;) {
            // normally blocking
            if (($result = static::read($socket)) !== false) {
                static::write($socket, call_user_func($this->procedure, $result));
            } else {
                break;
            }

            pcntl_signal_dispatch();
        }
    }

    /**
     * Signal handler for a worker process
     *
     * @param   int   $signal
     * @return  void
     */
    protected function handler($signal)
    {
        stream_set_blocking($this->sockets[0], 0);
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
