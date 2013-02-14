<?php

namespace Lopi\Bundle\PusherBundle\Tests\Pusher;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Lopi\Bundle\PusherBundle\Serializer\PusherSerializer;
use Symfony\Component\Serializer\Serializer;

use Lopi\Bundle\PusherBundle\Pusher\Pusher;

class PusherTest extends \PHPUnit_Framework_TestCase
{
    public function testTrigger()
    {
        $options = array(
            'host' => 'http://api.pusherapp.com'
        );

        $serializer = new Serializer(array(), array(new \Symfony\Component\Serializer\Encoder\JsonEncoder()));
        $pusher = new Pusher('5818', '91ec2b8176b5473feafa', '3e39e8aac04bcf06ca78', $options, $serializer);
        $this->assertTrue($pusher->trigger('test_channel', 'test_event', 'Test'));
    }



}