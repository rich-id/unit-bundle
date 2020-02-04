<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection;

use Doctrine\Bundle\FixturesBundle\DependencyInjection\CompilerPass\FixturesCompilerPass;
use Liip\TestFixturesBundle\Factory\ConnectionFactory;
use RichCongress\Bundle\UnitBundle\DataFixture\DataFixtureInterface;
use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\DataFixturesPass;
use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\OverrideServicesPass;
use RichCongress\Bundle\UnitBundle\OverrideService\LoggerStub;
use RichCongress\Bundle\UnitBundle\OverrideService\OverrideServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RichCongressUnitExtension extends Extension implements PrependExtensionInterface
{
    public const DEFAULT_OVERRIDE_SERVICE = [
        LoggerStub::class,
    ];

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function prepend(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        if ($config['enable_db_caching']) {
            $container->prependExtensionConfig(
                'liip_test_fixtures',
                [
                    'cache_db' => [
                        'sqlite' => 'liip_test_fixtures.services_database_backup.sqlite',
                    ]
                ]
            );
        }

        $container->prependExtensionConfig(
            'dama_doctrine_test',
            [
                'enable_static_connection' => true,
                'enable_static_meta_data_cache' => true,
                'enable_static_query_cache' => true,
            ]
        );

        $container->setParameter(
            'doctrine.dbal.connection_factory.class',
            ConnectionFactory::class
        );

        $container->prependExtensionConfig(
            'doctrine',
            [
                'dbal' => [
                    'driver' => 'pdo_sqlite',
                    'user'   => 'test',
                    'dbname' => 'test',
                    'path'   => '%kernel.cache_dir%/__DBNAME__.db',
                    'url'    => null,
                    'memory' => false,
                ]
            ]
        );
    }

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @return void
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('rich_congress_unit', $config);
        $this->setParameters($container, 'rich_congress_unit', $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if ($container->getParameter('kernel.environment') === 'unit_bundle_test') {
            $loader->load('../../Tests/Resources/config/services.yml');
        }

        $this->autoconfigure($container);
        $this->addDefaultOverrideServices($container);
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $name
     * @param array            $config
     *
     * @return void
     */
    protected function setParameters(ContainerBuilder $container, $name, array $config): void
    {
        foreach ($config as $key => $parameter) {
            $container->setParameter($name . '.' . $key, $parameter);

            if (is_array($parameter)) {
                $this->setParameters($container, $name . '.' . $key, $parameter);
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    protected function autoconfigure(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(OverrideServiceInterface::class)->addTag(OverrideServicesPass::OVERRIDE_SERVICE_TAG);
        $container->registerForAutoconfiguration(DataFixtureInterface::class)
            ->addTag(DataFixturesPass::DATA_FIXTURE_TAG)
            ->addTag(FixturesCompilerPass::FIXTURE_TAG);
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    protected function addDefaultOverrideServices(ContainerBuilder $container): void
    {
        foreach (static::DEFAULT_OVERRIDE_SERVICE as $overrideService) {
            /** @var OverrideServiceInterface $service */
            $service = new $overrideService();
            $parameterKey = 'rich_congress_unit.default_stubs.'. $service->getOverridenServiceName();

            if ($container->hasParameter($parameterKey) && $container->getParameter($parameterKey)) {
                $definition = new Definition($overrideService);
                $definition->setPublic(true);
                $definition->addTag(OverrideServicesPass::OVERRIDE_SERVICE_TAG);

                $container->setDefinition($overrideService, $definition);
            }
        }
    }
}
