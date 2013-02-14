<?php

namespace Lopi\Bundle\PusherBundle\Pusher;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Pusher
{

    private $pusher;
    private $serializer;

    public function __construct($appId, $key, $secret, $options, $serializer = null)
    {
        $this->pusher = new \Pusher($key, $secret, $appId, $options);
        $this->serializer = $serializer;
    }

    private function serialize($data) {
        if ($this->serializer !== null) {
            return $this->serializer->serialize($data, 'json');
        } else {
            return json_encode($data, true);
        }
    }

    
    public function trigger($channelName, $eventName, $body, $socketId = null)
    {
        $result = $this->pusher->trigger(array($channelName), $eventName,
            $this->serialize($body), $socketId, true, true);
        if ($result['status'] != 200) {
        $statusCode = $result['status'];
            $message = $result['body'];
            throw new HttpException($statusCode, $message);
        } else {
            return true;
        }
    }

    public function getChannelAuth($channelName, $socketId, $userId = null, $userInfo = false)
    {
        if (strpos($channelName, 'presence') === 0) {
            return $this->pusher->presence_auth($channelName, $socketId, $userId, $userInfo);
        } else {
            return $this->pusher->socket_auth($channelName, $socketId);
        }
    }

}
