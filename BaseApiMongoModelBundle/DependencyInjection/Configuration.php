<?php

namespace OpenOrchestra\BaseApiMongoModelBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_orchestra_base_api_model');

        $rootNode->children()
            ->arrayNode('factory')
                ->beforeNormalization()
                    ->ifTrue(function($v) { return $v === null; })
                    ->then(function($v) { return array(); })
                ->end()
                ->prototype('scalar')->end()
                ->defaultValue(array())
            ->end()
            ->arrayNode('document')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('api_client')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\BaseApiMongoModelBundle\Document\ApiClient')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\BaseApiMongoModelBundle\Repository\ApiClientRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('access_token')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\BaseApiMongoModelBundle\Document\AccessToken')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\BaseApiMongoModelBundle\Repository\AccessTokenRepository')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
