<?php

namespace OpenOrchestra\BaseApiBundle\DependencyInjection;

use OpenOrchestra\BaseApiMongoModelBundle\DependencyInjection\OpenOrchestraBaseApiMongoModelExtension;
use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class OpenOrchestraBaseApiMongoModelExtensionTest
 */
class OpenOrchestraBaseApiMongoModelExtensionTest extends AbstractBaseTestCase
{
    /**
     * @param string $className
     * @param string $name
     * @param bool|false $filter
     *
     * @dataProvider provideDocumentClass
     */
    public function testDefaultConfig($className, $name, $filter = false)
    {
        $class = 'OpenOrchestra\BaseApiMongoModelBundle\Document\\'. $className;
        $repository = 'OpenOrchestra\BaseApiMongoModelBundle\Repository\\'. $className . 'Repository';
        $container = $this->loadContainerFromFile('empty');
        $this->assertEquals($class, $container->getParameter('open_orchestra_api.document.' . $name . '.class'));
        $this->assertDefinition($container->getDefinition('open_orchestra_api.repository.' . $name), $class, $repository, $filter);
    }

    /**
     * @return array
     */
    public function provideDocumentClass()
    {
        return array(
            array("ApiClient", "api_client"),
            array("AccessToken", "access_token")
        );
    }

    /**
     * @param string     $class
     * @param string     $name
     * @param string     $repository
     * @param bool|false $filter
     *
     * @dataProvider provideDocumentClassFakeValue
     */
    public function testConfigWithValue($class, $name, $repository, $filter = false)
    {
        $container = $this->loadContainerFromFile('value');
        $this->assertEquals($class, $container->getParameter('open_orchestra_api.document.' . $name . '.class'));
        $this->assertDefinition($container->getDefinition('open_orchestra_api.repository.' . $name), $class, $repository, $filter);
    }

    /**
     * @return array
     */
    public function provideDocumentClassFakeValue()
    {
        return array(
            array("FakeClassApiClient", "api_client", "FakeRepositoryApiClient"),
            array("FakeClassAccessToken", "access_token", "FakeRepositoryAccesToken")
        );
    }

    /**
     * @param Definition $definition
     * @param string     $class
     * @param string     $repository
     * @param bool|false $filterType
     */
    private function assertDefinition(Definition $definition, $class, $repository, $filterType = false)
    {
        $this->assertSame($definition->getClass(), $repository);
        $factory = $definition->getFactory();
        $this->assertSame($factory[1], "getRepository");
        $this->assertSame($definition->getArgument(0), $class);
        $this->assertTrue($definition->hasMethodCall('setAggregationQueryBuilder'));
        if ($filterType) {
            $this->assertTrue($definition->hasMethodCall('setFilterTypeManager'));
        }
    }

    /**
     * @param string $file
     *
     * @return ContainerBuilder
     */
    private function loadContainerFromFile($file)
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);
        $container->setParameter('kernel.cache_dir', '/tmp');
        $container->registerExtension(new OpenOrchestraBaseApiMongoModelExtension());

        $locator = new FileLocator(__DIR__ . '/Fixtures/config/');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load($file . '.yml');
        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}
