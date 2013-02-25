<?php

namespace Lopi\Bundle\PusherBundle\Pusher;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Lopi\Bundle\PusherBundle\Exception\PusherException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Pusher
{

    private $pusher;
    private $serializer;

    public function __construct($appId, $key, $secret, $options, $serializer = null)
    {

        $this->pusher = new \Pusher($key, $secret, $appId, true,
            isset($options['host']) ? $options['host'] : null,
            isset($options['port']) ? $options['port'] : null,
            isset($options['timeout']) ? $options['timeout'] : null);
    }


    
    public function trigger($channelName, $eventName, $body, $socketId = null)
    {
        $result = $this->pusher->trigger(array($channelName), $eventName, $body, $socketId, true, true);
        if ($result['status'] != 200) {
            $statusCode = $result['status'];
            $body = $result['body'];
            throw new PusherException($statusCode, $body, "trigger failed");
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
