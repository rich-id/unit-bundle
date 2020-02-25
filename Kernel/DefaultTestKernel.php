<?php

namespace RichCongress\Bundle\UnitBundle\Kernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Liip\FunctionalTestBundle\LiipFunctionalTestBundle;
use Liip\TestFixturesBundle\LiipTestFixturesBundle;
use RichCongress\Bundle\UnitBundle\RichCongressUnitBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

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

    /**
     * @return array|iterable|BundleInterface[]
     */
    public function registerBundles()
    {
        return [
            new DoctrineBundle(),
            new DoctrineFixturesBundle(),
            new FrameworkBundle(),
            new LiipFunctionalTestBundle(),
            new LiipTestFixturesBundle(),
            new DAMADoctrineTestBundle(),
            new RichCongressUnitBundle(),
        ];
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
        $loader->load([$this, 'configureContainer']);
        $confDir = $this->getConfigurationDir();

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
}
