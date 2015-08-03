<?php

namespace OpenOrchestra\BaseApiMongoModelBundle;

use OpenOrchestra\BaseApiMongoModelBundle\DependencyInjection\Compiler\EntityResolverCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class OpenOrchestraBaseApiMongoModelBundle
 */
class OpenOrchestraBaseApiMongoModelBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EntityResolverCompilerPass());
    }
}
