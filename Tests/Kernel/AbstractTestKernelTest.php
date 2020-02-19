<?php

namespace RichCongress\Bundle\UnitBundle\Tests\Kernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Liip\FunctionalTestBundle\LiipFunctionalTestBundle;
use Liip\TestFixturesBundle\LiipTestFixturesBundle;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use RichCongress\Bundle\UnitBundle\Kernel\AbstractTestKernel;
use RichCongress\Bundle\UnitBundle\RichCongressUnitBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Class AbstractTestKernelTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Kernel
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Kernel\AbstractTestKernel
 */
class AbstractTestKernelTest extends MockeryTestCase
{
    /**
     * @return void
     */
    public function testRegisterBundles(): void
    {
        /** @var AbstractTestKernel|MockInterface $kernel */
        $kernel = \Mockery::mock(AbstractTestKernel::class)->makePartial();
        $bundles = $kernel->registerBundles();

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
}
