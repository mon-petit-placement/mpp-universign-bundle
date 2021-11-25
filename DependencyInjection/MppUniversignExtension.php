<?php

declare(strict_types=1);

namespace Mpp\UniversignBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MppUniversignExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter(
            sprintf('%s.entrypoint', Configuration::CONFIGURATION_ROOT),
            $config['entrypoint']
        );

        $container->setParameter(
            sprintf('%s.options', Configuration::CONFIGURATION_ROOT),
            $config['options']
        );

        $container->setParameter(
            sprintf('%s.client_options', Configuration::CONFIGURATION_ROOT),
            $config['client_options']
        );
    }
}
