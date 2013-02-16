<?php

namespace Lopi\Bundle\PusherBundle\Exception;

class PusherException extends \RuntimeException
{
    private $status;
    private $body;

    public function __construct($status, $body, $message = null)
    {
        $this->status = $status;
        $this->body = $body;
        parent::__construct($message);
    }


    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }






}
