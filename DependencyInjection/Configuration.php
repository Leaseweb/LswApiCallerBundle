<?php

namespace Lsw\ApiCallerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lsw_api_caller');

        $rootNode
            ->useAttributeAsKey('')
            ->prototype('array')
                ->children()
                    ->scalarNode('endpoint')
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->validate()
                            ->always( function($value) { return filter_var($value, FILTER_VALIDATE_URL);} )->end()
                    ->end()
                    ->scalarNode('format')
                        ->cannotBeEmpty()
                        ->isRequired()
                    ->end()
                    ->scalarNode('replace_engine')
                        ->defaultValue(false)
                    ->end()
                    ->arrayNode('engine')
                        ->useAttributeAsKey('')
                        ->prototype('scalar')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}