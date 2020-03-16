<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Utility;

use RichCongress\Bundle\UnitBundle\TestCase\TestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\Tests\Resources\OverrideService\DummyOverrideService;
use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class OverrideServicesUtility
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Utility
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility
 */
class OverrideServicesUtilityTest extends TestCase
{
    /**
     * @var OverrideServicesUtility
     */
    protected $utility;

    /**
     * @return void
     */
    protected function beforeTest(): void
    {
        $this->utility = new OverrideServicesUtility();
    }

    /**
     * @return void
     */
    public function testAddOverrideServiceAndOverrideServices(): void
    {
        $this->utility->addOverrideServiceClass(DummyOverrideService::class);
        $this->utility->addOverrideServiceClass(DummyOverrideService::class);
        $this->utility->addOverrideServiceClass(DummyOverrideService::class);

        self::assertContains('test.service', $this->utility->getOverridenServiceIds());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testOverrideService(): void
    {
        $container = new Container();
        $container->set('override-service', new DummyEntity());

        OverrideServicesUtility::overrideService($container, 'override-service', new DummyOverrideService());

        self::assertInstanceOf(DummyOverrideService::class, $container->get('override-service'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testOverrideServiceForce(): void
    {
        $container = new Container();

        OverrideServicesUtility::overrideService($container, 'service_container', new DummyOverrideService());

        self::assertInstanceOf(DummyOverrideService::class, $container->get('service_container'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testOverrideServiceFail(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Impossible to override the service "override-service".');

        $container = \Mockery::mock(ContainerInterface::class);
        $container->shouldReceive('set')->andThrow(new \Exception());

        OverrideServicesUtility::overrideService($container, 'override-service', new DummyOverrideService());
    }
}

