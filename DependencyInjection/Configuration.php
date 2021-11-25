<?php

declare(strict_types=1);

namespace Mpp\UniversignBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const CONFIGURATION_ROOT = 'mpp_universign';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::CONFIGURATION_ROOT);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('entrypoint')
                    ->children()
                        ->scalarNode('sign')->isRequired()->end()
                        ->scalarNode('ra')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('registration_callback_route_name')->defaultValue('mpp_universign_callback')->cannotBeEmpty()->end()
                        ->scalarNode('success_redirection_route_name')->defaultNull()->end()
                        ->scalarNode('cancel_redirection_route_name')->defaultNull()->end()
                        ->scalarNode('fail_redirection_route_name')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('client_options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('timeout')->defaultValue(10)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
