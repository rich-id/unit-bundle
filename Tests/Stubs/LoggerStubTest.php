<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Stubs;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\Stubs\LoggerStub;

/**
 * Class LoggerStubTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Stubs\LoggerStub
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
    }

    /**
     * @return void
     */
    public function testLogsLevelAndClear(): void
    {
        $this->logger->emergency('message1', []);
        $this->logger->alert('message2', []);
        $this->logger->critical('message3', []);
        $this->logger->error('message4', []);
        $this->logger->warning('message5', []);
        $this->logger->notice('message6', []);
        $this->logger->info('message7', []);
        $this->logger->debug('message8', []);

        $logs = $this->logger->getLogs();

        self::assertCount(8, $logs);

        $this->logger->clearLogs();
        $logs = $this->logger->getLogs();

        self::assertEmpty($logs);
    }
}
