<?php

namespace OpenOrchestra\BaseApiMongoModelBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class EntityResolverCompilerPass
 */
class EntityResolverCompilerPass implements  CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('doctrine_mongodb.odm.listeners.resolve_target_document')) {
            $resourcePath = '.';
            foreach ($container->getResources() as $resource) {
                if (!$resource instanceof FileResource) {
                    continue;
                }
                $resourcePath = (string) $resource;
                if (is_string($resourcePath)) {
                    if (strpos($resourcePath, 'BaseApiMongoModelBundle')
                        && strpos($resourcePath, 'config')
                        && strpos($resourcePath, 'yml') === false
                        && strpos($resourcePath, 'validation') === false
                    ) {
                        break;
                    }
                }
            }

            $defaultResolveDocument = Yaml::parse(file_get_contents($resourcePath . '/resolve_document.yml'))['resolve_target_documents'];

            $definition = $container->findDefinition('doctrine_mongodb.odm.listeners.resolve_target_document');
            $definitionCalls = $definition->getMethodCalls();
            foreach ($defaultResolveDocument as $interface => $class) {
                if (!$this->resolverExist($definitionCalls, $interface)) {
                    $definition->addMethodCall('addResolveTargetDocument', array($interface, $class, array()));
                }
            }
        }
    }

    /**
     * Check if model interface are already resolved
     *
     * @param $methodCalls
     * @param $interface
     *
     * @return bool
     */
    protected function resolverExist($methodCalls, $interface)
    {
        foreach ($methodCalls as $call) {
            if ($call[0] === 'addResolveTargetDocument' && $call[1][0] === $interface) {
                return true;
            }
        }

        return false;
    }
}
