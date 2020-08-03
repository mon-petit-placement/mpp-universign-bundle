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
                ->scalarNode('host')->isRequired()->end()
                ->scalarNode('scheme')->isRequired()->end()
                ->scalarNode('url_path')->isRequired()->end()
                ->scalarNode('user_email')->isRequired()->end()
                ->scalarNode('user_password')->isRequired()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
