<?php

namespace OpenOrchestra\BaseApiMongoModelBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OpenOrchestraBaseApiMongoModelExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $factoryService = null;
        $factory = $config['factory'];
        if (count($factory) > 0) {
            $factoryService = $factory[0];
        }

        if (is_null($factoryService) && class_exists('Doctrine\ODM\MongoDB\DocumentManager')) {
            $factoryService = 'doctrine.odm.mongodb.document_manager';
        }

        foreach ($config['document'] as $class => $content) {
            if (is_array($content)) {
                $container->setParameter('open_orchestra_api.document.' . $class . '.class', $content['class']);
                if (array_key_exists('repository', $content)) {
                    $definition = new Definition($content['repository'], array($content['class']));
                    $definition->setFactory(array(new Reference($factoryService), 'getRepository'));
                    $definition->addMethodCall('setAggregationQueryBuilder', array(
                        new Reference('doctrine_mongodb.odm.default_aggregation_query')
                    ));
                    if (method_exists($content['repository'],'setFilterTypeManager')) {
                        $definition->addMethodCall('setFilterTypeManager', array(
                            new Reference('open_orchestra_pagination.filter_type.manager')
                        ));
                    }
                    $container->setDefinition('open_orchestra_api.repository.' . $class, $definition);
                }
            }
        }

        $container->setAlias('object_manager', $factoryService);
    }
}
