<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class ParallelIterator implements \Iterator, \Countable
{
    /**
     * Sockets for interprocess communication
     *
     * @var array
     */
    private $sockets = array();

    /**
     * The queue of processing data
     *
     * @var \SplQueue
     */
    private $queue;

    /**
     * Processing results
     *
     * @var \SplQueue
     */
    private $results;

    /**
     * The remaining number of tasks
     *
     * @var int
     */
    private $remains = 0;

    /**
     * The job to execute in child processes
     *
     * @var callable
     */
    private $job;

    /**
     * The timeout of a IO wait
     *
     * @var int
     */
    private $timeout;

    /**
     * @param callable $job     The Job to execute in child processes
     * @param int      $timeout The timeout of IO wait
     */
    public function __construct(callable $job, $timeout = null)
    {
        $this->queue = new \SplQueue();
        $this->results = new \SplQueue();
        $this->job = $job;
        $this->timeout = $timeout;
    }

    /**
     * Close sockets and kill workers.
     */
    public function __destruct()
    {
        foreach ($this->sockets as $pid => $socket) {
            fclose($socket);
            $this->signal($pid, SIGTERM);
            pcntl_waitpid($pid, $status, WUNTRACED);
        }
    }

    /**
     * Start a worker process.
     *
     * @return int The forked process ID
     */
    public function fork()
    {
        $pair = $this->pair();
        $pid = pcntl_fork();

        if ($pid > 0) {
            // in parent process
            fclose($pair[1]);
            $this->sockets[$pid] = $pair[0];
        } elseif ($pid === 0) {
            // in worker process
            fclose($pair[0]);
            foreach ($this->sockets as $socket) {
                fclose($socket);
            }
            $this->sockets = [];
            $this->loop($pair[1]);
            exit;
        } else {
            throw new \RuntimeException('Failed to fork child process.');
        }

        return $pid;
    }

    /**
     * Stop one worker process.
     *
     * @return int The process ID to be stopped
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
     * Return the number of processes.
     *
     * @return int
     */
    public function processes()
    {
        return count($this->sockets);
    }

    /**
     * Push a value to the queue.
     *
     * @param mixed $value
     * @return void
     */
    public function push($value)
    {
        $this->queue->enqueue($value);
        $this->flush();
    }

    /**
     * Push all values to the queue.
     *
     * @param array $values
     * @return void
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
     * @return mixed
     */
    public function result()
    {
        $this->fill();
        return $this->results->isEmpty() ? null : $this->results->dequeue();
    }

    /**
     * Return a current result.
     *
     * @see \Iterator
     * @return mixed
     */
    public function current()
    {
        return $this->results->bottom();
    }

    /**
     * @see \Iterator
     * @return mixed
     */
    public function key()
    {
        return null;
    }

    /**
     * @see \Iterator
     */
    public function next()
    {
        $this->results->dequeue();
        $this->fill();
    }

    /**
     * @see \Iterator
     */
    public function rewind()
    {
        $this->fill();
    }

    /**
     * @see \Iterator
     * @return boolean
     */
    public function valid()
    {
        return !$this->results->isEmpty();
    }

    /**
     * Remaining number of tasks.
     *
     * @see \Countable
     * @return int
     */
    public function count()
    {
        return $this->remains;
    }

    /**
     * Send the signal.
     *
     * @param int $pid    Process ID
     * @param int $signal Signal to sent
     * @return boolean
     */
    protected function signal($pid, $signal)
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
     * @return array
     */
    protected function pair()
    {
        return stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
    }

    /**
     * Read data with unserialize from a socket.
     *
     * @param resource $socket
     * @throws \RuntimeException
     */
    protected function read($socket)
    {
        $result = fgets($socket);
        if ($result === false) {
            throw new \RuntimeException();
        }
        return $this->unserialize($result);
    }

    /**
     * Write data with serialize to a socket.
     *
     * @param resource $socket
     * @param mixed    $value
     * @return boolean
     */
    protected function write($socket, $value)
    {
        $value = $this->serialize($value) . PHP_EOL;
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
     * @param  mixed $source
     * @return string
     */
    protected function serialize($source)
    {
        return base64_encode(serialize($source));
    }

    /**
     * Unserialize data
     *
     * @param  mixed $source
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function unserialize($source)
    {
        $source = base64_decode($source);
        $result = @unserialize($source);
        if ($result === false && $source !== serialize(false)) {
            throw new \UnexpectedValueException();
        }
        return $result;
    }

    /**
     * Read process results.
     * Block when results are empty.
     *
     * @return void
     */
    private function fill()
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
                    $result = $this->read($socket);
                } catch (\RuntimeException $e) {
                    continue;
                }
                $this->results->enqueue($result);
                $this->remains--;
            }
        }
    }

    /**
     * Write queueing values (might block).
     */
    private function flush()
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
                if ($this->write($socket, $this->queue->dequeue()) !== false) {
                    $this->remains++;
                }
            }
        }
    }

    /**
     * The loop for worker processes to processing a task.
     *
     * @param resource $socket
     */
    private function loop($socket)
    {
        while (true) {
            try {
                $result = $this->read($socket);  // is blocking
            } catch (\RuntimeException $e) {
                break;
            }
            $this->write($socket, call_user_func($this->job, $result));
        }
    }
}
