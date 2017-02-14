<?php

namespace Everlution\FileJetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('everlution_file_jet');

        $rootNode
            ->children()
                ->arrayNode('storages')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('id')
                                ->isRequired()
                            ->end()
                            ->scalarNode('api_key')
                                ->isRequired()
                            ->end()
                            ->scalarNode('name')
                                ->isRequired()
                            ->end()
                            ->scalarNode('prefix')
                                ->defaultValue('')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
