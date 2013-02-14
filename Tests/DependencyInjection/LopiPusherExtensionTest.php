<?php

namespace Lopi\Bundle\PusherBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use JMS\SerializerBundle\DependencyInjection\JMSSerializerExtension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Serializer\Serializer;

use Lopi\Bundle\PusherBundle\DependencyInjection\LopiPusherExtension;
use Lopi\Bundle\PusherBundle\Pusher\Pusher;

class PusherTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
		$container = new ContainerBuilder();
        $container->setDefinition('serializer', new Definition('Symfony\Component\Serializer\Serializer'));

        $configs = array('lopi_pusher' => array(
			'app_id' => 'app_id',
			'key' => 'key',
			'secret' => 'secret',
            'auth_service_id' => 'acme_service_id',
            'serializer_service_id' => 'serializer'
			));

		$extension = new LopiPusherExtension();
		$extension->load($configs, $container);

        $expectedPusher = new Pusher('app_id', 'key', 'secret', array(), new Serializer());
		$this->assertEquals($expectedPusher, $container->get('lopi_pusher.pusher'));

        $this->assertEquals('app_id', $container->getParameter('lopi_pusher.app.id'));
		$this->assertEquals('key', $container->getParameter('lopi_pusher.key'));
		$this->assertEquals('secret', $container->getParameter('lopi_pusher.secret'));
		$this->assertEquals('acme_service_id', (string) $container->getAlias('lopi_pusher.authenticator'));
        $this->assertEquals('Symfony\Component\Serializer\Serializer', get_class($container->get('lopi_pusher.serializer')));
    }




}