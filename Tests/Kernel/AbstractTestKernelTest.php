<?php

namespace RichCongress\Bundle\UnitBundle\Tests\Kernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Liip\FunctionalTestBundle\LiipFunctionalTestBundle;
use Liip\TestFixturesBundle\LiipTestFixturesBundle;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use RichCongress\Bundle\UnitBundle\Kernel\DefaultTestKernel;
use RichCongress\Bundle\UnitBundle\RichCongressUnitBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Class AbstractTestKernelTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Kernel
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Kernel\DefaultTestKernel
 */
class AbstractTestKernelTest extends MockeryTestCase
{
    /**
     * @var DefaultTestKernel|MockInterface
     */
    protected $kernel;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->kernel = \Mockery::mock(DefaultTestKernel::class)->makePartial();
    }

    /**
     * @return void
     */
    public function testRegisterBundles(): void
    {
        $bundles = $this->kernel->registerBundles();

        self::assertCount(7, $bundles);
        self::assertContainsOnlyInstancesOf(BundleInterface::class, $bundles);
        self::assertInstanceOf(DoctrineBundle::class, $bundles[0]);
        self::assertInstanceOf(DoctrineFixturesBundle::class, $bundles[1]);
        self::assertInstanceOf(FrameworkBundle::class, $bundles[2]);
        self::assertInstanceOf(LiipFunctionalTestBundle::class, $bundles[3]);
        self::assertInstanceOf(LiipTestFixturesBundle::class, $bundles[4]);
        self::assertInstanceOf(DAMADoctrineTestBundle::class, $bundles[5]);
        self::assertInstanceOf(RichCongressUnitBundle::class, $bundles[6]);
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    public function testRegisterContainerConfigurationWithNoConfigurationDirectory(): void
    {
        self::assertNull($this->kernel->getConfigurationDir());

        $loader = \Mockery::mock(LoaderInterface::class);
        $loader->shouldReceive('load')->once();

        $this->kernel->registerContainerConfiguration($loader);
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    public function testRegisterContainerConfigurationWithConfigurationDirectory(): void
    {
        $loader = \Mockery::mock(LoaderInterface::class);
        $loader->shouldReceive('load')->times(5);

        $this->kernel
            ->shouldReceive('getConfigurationDir')
            ->once()
            ->andReturn('../');

        $this->kernel->registerContainerConfiguration($loader);
    }

    /**
     * @return void
     */
    public function testConfigureContainer(): void
    {
        $container = \Mockery::mock(ContainerBuilder::class);
        $container->shouldReceive('setParameter')->once();
        $container->shouldReceive('addObjectResource')->once();

        $this->kernel->configureContainer($container);
    }
}
