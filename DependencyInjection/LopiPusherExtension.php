<?php

namespace Lopi\Bundle\PusherBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

class LopiPusherExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('config.xml');
        
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        if (!isset($config['app_id']) || $config['app_id'] === null) {
            throw new \InvalidArgumentException('Please set the app_id of Pusher');
        }
        
        $container->setParameter('lopi_pusher.app.id', $config['app_id']);
        
        if (!isset($config['key']) || $config['key'] === null) {
            throw new \InvalidArgumentException('Please set the key of Pusher');
        }
        
        $container->setParameter('lopi_pusher.key', $config['key']);
        
        if (!isset($config['secret']) || $config['secret'] === null) {
            throw new \InvalidArgumentException('Please set the secret of Pusher');
        }
        
        $container->setParameter('lopi_pusher.secret', $config['secret']);


        if (isset($config['auth_service_id'])) {
            $container->setAlias('lopi_pusher.authenticator', $config['auth_service_id']);
        }



        $loader->load('services.xml');

        $options = $container->getParameter('lopi_pusher.options');

        if (!is_array($options)) {
            $options = array();
        }

        if ($config['host']) {
            $options['host'] = $config['host'];
        }
        if ($config['port']) {
            $options['port'] = $config['port'];
        }

        $container->setParameter('lopi_pusher.options', $options);


        if (isset($config['serializer_service_id'])) {
            $container->setAlias('lopi_pusher.serializer', $config['serializer_service_id']);
        }

    }

    public function getAlias()
    {
        return 'lopi_pusher';
    }
}
