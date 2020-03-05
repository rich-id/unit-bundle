<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestTrait;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\OverrideService\DummyOverrideService;

/**
 * Class OverrideServiceTraitTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestTrait
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestTrait\OverrideServiceTrait
 */
class OverrideServiceTraitTest extends TestCase
{
    /**
     * @var DummyOverrideService
     */
    protected $service;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DummyOverrideService();
    }

    /**
     * @return void
     */
    public function testGetOverridenServiceNames(): void
    {
        self::assertContains('test.service', DummyOverrideService::getOverridenServiceNames());
    }

    /**
     * @return void
     */
    public function testSetUpAndTearDownExists(): void
    {
        self::assertContains('test.service', DummyOverrideService::$overridenServices);
        $this->service->setUp();
        $this->service->__destruct();
    }
}
