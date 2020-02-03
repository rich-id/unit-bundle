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
    public function testGetOverridenServiceName(): void
    {
        self::assertSame('test.service', $this->service->getOverridenServiceName());
    }

    /**
     * @return void
     */
    public function testSetUpAndTearDownExists(): void
    {
        self::assertSame('test.service', $this->service::OVERRIDEN_SERVICE);
        $this->service->setUp();
        $this->service->tearDown();
    }
}
