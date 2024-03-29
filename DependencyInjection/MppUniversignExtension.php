<?php

declare(strict_types=1);

namespace Mpp\UniversignBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MppUniversignExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration($container->getParameter('kernel.debug'));
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
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
