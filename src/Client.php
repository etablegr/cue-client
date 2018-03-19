<?php

namespace Cue;

class Client
{
    const USER_AGENT = 'yrizos/cue-client';
    const DEFAULT_DELAY = 0;
    const DEFAULT_PORT = 8000;

    /** @var string */
    private $url;

    /** @var string */
    private $port = self::DEFAULT_PORT;

    private $timeout = 30;

    public function getUrl()
    {
        return $this->url;
    }

    public function __construct($url, $port = SELF::DEFAULT_PORT)
    {
        $this->setUrl($url)
             ->setPort($port);
    }

    public function setUrl($url)
    {
        $url = trim(strval($url));

        if (empty($url)) {
            throw new \InvalidArgumentException('URL is empty');
        }

        $this->url = $url;

        return $this;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $port = trim(strval($port));

        if (empty($port) || !is_numeric($port)) {
            $port = self::DEFAULT_PORT;
        }

        $this->port = $port;

        return $this;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function setTimeout($timeout)
    {
        $timeout = intval($timeout);

        if ($timeout < 0) {
            $timeout = 0;
        }

        $this->timeout = $timeout;

        return $this;
    }

    public function queueTask(Task $task)
    {
        $content = $task->toJson();
        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($content),
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->getTimeout());
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_PORT, $this->getPort());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

        if (curl_exec($ch) === false) {
            throw new \RuntimeException(curl_error($ch), curl_errno($ch));
        }

        curl_close($ch);

        return $this;
    }
}