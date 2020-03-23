<?php

namespace RichCongress\Bundle\UnitBundle\Kernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Liip\FunctionalTestBundle\LiipFunctionalTestBundle;
use Liip\TestFixturesBundle\LiipTestFixturesBundle;
use RichCongress\Bundle\UnitBundle\RichCongressUnitBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class DefaultTestKernel
 *
 * @package   RichCongress\Bundle\UnitBundle\Kernel
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DefaultTestKernel extends Kernel
{
    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';
    public const DEFAULT_BUNDLES = [
        DoctrineBundle::class,
        DoctrineFixturesBundle::class,
        FrameworkBundle::class,
        LiipFunctionalTestBundle::class,
        LiipTestFixturesBundle::class,
        DAMADoctrineTestBundle::class,
        RichCongressUnitBundle::class,
    ];

    /**
     * @return array|iterable|BundleInterface[]
     */
    public function registerBundles(): iterable
    {
        $configDir = $this->getConfigurationDir();

        foreach (static::DEFAULT_BUNDLES as $class) {
            yield new $class();
        }

        if ($this->getConfigurationDir() !== null && file_exists($configDir . '/bundles.php')) {
            $contents = require $configDir . '/bundles.php';

            foreach ($contents as $class => $envs) {
                $appropriateEnv = $envs[$this->environment] ?? $envs['all'] ?? false;
                $isAlreadyLoaded = array_key_exists($class, static::DEFAULT_BUNDLES);

                if ($appropriateEnv && !$isAlreadyLoaded) {
                    yield new $class();
                }
            }
        }
    }

    /**
     * @return string|null
     */
    public function getConfigurationDir(): ?string
    {
        return null;
    }

    /**
     * @param LoaderInterface $loader Resource loader.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $confDir = $this->getConfigurationDir();
        $loader->load(
            \Closure::fromCallable([$this, 'configureContainer'])
        );

        if ($confDir === null) {
            return;
        }

        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{packages}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function configureContainer(ContainerBuilder $container): void
    {
        $container->setParameter('container.dumper.inline_class_loader', true);
        $container->addObjectResource($this);
    }

    /**
     * @param RouteCollectionBuilder $routes
     *
     * @return void
     *
     * @throws LoaderLoadException
     */
    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getConfigurationDir();

        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }
}
