<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\OverrideService;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\OverrideService\LoggerStub;

/**
 * Class LoggerStubTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\OverrideService\LoggerStub
 */
class LoggerStubTest extends TestCase
{
    /**
     * @var LoggerStub
     */
    protected $logger;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->logger = new LoggerStub();
        $this->logger->setUp();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->logger->tearDown();
    }

    /**
     * @return void
     */
    public function testLog(): void
    {
        self::assertEmpty(LoggerStub::$logs);

        $this->logger->emergency('Alert!');

        self::assertCount(1, LoggerStub::$logs);
    }

    /**
     * @return void
     */
    public function testGetOverridenServiceName(): void
    {
        self::assertSame('logger', $this->logger->getOverridenServiceName());
    }
}
