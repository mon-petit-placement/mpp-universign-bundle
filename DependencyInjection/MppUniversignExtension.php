<?php

declare(strict_types=1);

namespace Mpp\UniversignBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MppUniversignExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration($container->getParameter('kernel.debug'));
        $config = $this->processConfiguration($configuration, $configs);
        // $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        // $loader->load('services.yaml');

        $container->setParameter(
            sprintf('%s.host', Configuration::CONFIGURATION_ROOT),
            $config['host']
        );
        $container->setParameter(
            sprintf('%s.scheme', Configuration::CONFIGURATION_ROOT),
            $config['scheme']
        );
        $container->setParameter(
            sprintf('%s.url_path', Configuration::CONFIGURATION_ROOT),
            $config['url_path']
        );
        $container->setParameter(
            sprintf('%s.user_email', Configuration::CONFIGURATION_ROOT),
            $config['user_email']
        );
        $container->setParameter(
            sprintf('%s.user_password', Configuration::CONFIGURATION_ROOT),
            $config['user_password']
        );
    }
}
