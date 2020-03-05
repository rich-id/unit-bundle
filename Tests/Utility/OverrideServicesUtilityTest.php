<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Utility;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use RichCongress\Bundle\UnitBundle\Stubs\ContainerStub;
use RichCongress\Bundle\UnitBundle\TestCase\TestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\OverrideService\DummyOverrideService;
use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;

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
        $service1 = new DummyOverrideService();
        $service2 = new DummyOverrideService();
        $service3 = new DummyOverrideService();

        $this->utility->addOverrideService($service1);
        $this->utility->addOverrideService($service2);
        $this->utility->addOverrideService($service3);

        self::assertContains('test.service', $this->utility->getOverridenServiceIds());
    }
}

