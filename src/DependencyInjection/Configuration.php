<?php

namespace EnderLab\TranslatableEntityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('translatable_entity');

        $treeBuilder
            ->getRootNode()
                ->children()
                    ->scalarNode('default_locale')->defaultValue('en')->end()
                ->end()
                ->children()
                    ->scalarNode('default_timezone')->defaultValue('Europe/London')->end()
                ->end()
                ->children()
                    ->arrayNode('availables_locales')
                        ->scalarPrototype()->end()
                    ->end()
                ->end()
                ->children()
                    ->arrayNode('availables_timezones')
                        ->scalarPrototype()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}