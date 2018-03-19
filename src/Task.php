<?php

namespace Cue;

class Task implements \Countable, \JsonSerializable
{
    /** @var string */
    private $task;

    /** @var string */
    private $queue;

    /** @var int */
    private $delay;

    private $messages = [];

    public function __construct(
        $queue,
        $task,
        array $messages = [],
        $delay = Client::DEFAULT_DELAY
    )
    {
        $this->setQueue($queue)
             ->setTask($task)
             ->setMessages($messages)
             ->setDelay($delay);
    }

    public function getQueue()
    {
        return $this->queue;
    }

    public function setQueue($queue)
    {
        $queue = trim(strval($queue));

        if (empty($queue)) {
            throw new \InvalidArgumentException();
        }

        $this->queue = $queue;

        return $this;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setTask($task)
    {
        $task = trim(strval($task));

        if (empty($task)) {
            throw new \InvalidArgumentException('Task is empty');
        }

        $this->task = $task;

        return $this;
    }

    public function getDelay()
    {
        return $this->delay;
    }

    public function setDelay($delay)
    {
        $delay = intval($delay);

        if ($delay < 0) {
            $delay = 0;
        }

        $this->delay = $delay;

        return $this;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMessages(array $messages = [])
    {
        $messages = array_filter($messages);
        $messages = array_values($messages);

        $this->messages = $messages;

        return $this;
    }

    public function addMessage($message)
    {
        if (!empty($message)) {
            $this->messages[] = $message;
        }

        return $this;
    }

    public function toArray()
    {
        return [
            'queue'    => $this->getQueue(),
            'task'     => $this->getTask(),
            'delay'    => $this->getDelay(),
            'messages' => $this->getMessages(),
        ];
    }

    public function toJson()
    {
        return json_encode($this);
    }

    public final function jsonSerialize()
    {
        return $this->toArray();
    }

    public final function count()
    {
        return count($this->messages);
    }
}