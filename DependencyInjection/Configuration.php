<?php

declare(strict_types=1);

namespace Mpp\UniversignBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const CONFIGURATION_ROOT = 'mpp_universign';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::CONFIGURATION_ROOT);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('entrypoint')
                    ->children()
                        ->scalarNode('url')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('options')
                    ->children()
                        ->scalarNode('registration_callback_route_name')->defaultValue('mpp_universign_callback')->cannotBeEmpty()->end()
                        ->scalarNode('success_redirection_route_name')->defaultNull()->end()
                        ->scalarNode('cancel_redirection_route_name')->defaultNull()->end()
                        ->scalarNode('fail_redirection_route_name')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
