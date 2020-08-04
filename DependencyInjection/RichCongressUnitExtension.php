<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection;

use Doctrine\Bundle\FixturesBundle\DependencyInjection\CompilerPass\FixturesCompilerPass;
use RichCongress\Bundle\UnitBundle\DataFixture\DataFixtureInterface;
use RichCongress\Bundle\UnitBundle\DataFixture\SqliteDatabaseBackup;
use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\DataFixturesPass;
use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\OverrideServicesPass;
use RichCongress\Bundle\UnitBundle\Doctrine\TestConnectionFactory;
use RichCongress\Bundle\UnitBundle\Stubs\LoggerStub;
use RichCongress\Bundle\UnitBundle\OverrideService\OverrideServiceInterface;
use RichCongress\BundleToolbox\Configuration\AbstractExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RichCongressUnitExtension extends AbstractExtension implements PrependExtensionInterface
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

        if ($config['db_cache']['enable']) {
            $container->prependExtensionConfig(
                'liip_test_fixtures',
                [
                    'cache_db' => [
                        'sqlite' => SqliteDatabaseBackup::class,
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

    }

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    protected function prependDoctrine(ContainerBuilder $container): void
    {
        $container->setParameter(
            'doctrine.dbal.connection_factory.class',
            TestConnectionFactory::class
        );

        $container->prependExtensionConfig(
            'doctrine',
            [
                'dbal' => [
                    'driver' => 'pdo_sqlite',
                    'user'   => 'test',
                    'path'   => '%kernel.cache_dir%/__DBNAME__.db',
                    'url'    => null,
                    'memory' => false,
                ],
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
        $this->parseConfiguration(
            $container,
            new Configuration(),
            $configs
        );

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
            $overridenServicesCallback = [$overrideService, 'getOverridenServiceNames'];

            foreach ($overridenServicesCallback() as $serviceName) {
                $parameterKey = 'rich_congress_unit.default_stubs.'. $serviceName;

                if ($container->hasParameter($parameterKey) && $container->getParameter($parameterKey)) {
                    $definition = new Definition($overrideService);
                    $definition->setPublic(true);
                    $definition->addTag(OverrideServicesPass::OVERRIDE_SERVICE_TAG);

                    $container->setDefinition($overrideService, $definition);
                }
            }
        }
    }
}
