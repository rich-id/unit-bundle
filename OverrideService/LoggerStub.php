<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\OverrideService;

use RichCongress\Bundle\UnitBundle\Stubs\LoggerStub as BaseLoggerStub;

/**
 * Class LoggerStub
 *
 * @package   RichCongress\Bundle\UnitBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class LoggerStub extends BaseLoggerStub implements OverrideServiceInterface
{
    /**
     * @var array
     */
    public static $logs;

    /**
     * @inheritDoc
     */
    public function getOverridenServiceName(): ?string
    {
        return 'logger';
    }

    /**
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array()): void
    {
        parent::log($level, $message, $context);

        static::$logs = $this->getLogs();
    }

    /**
     * @return void
     */
    public function clearLogs(): void
    {
        parent::clearLogs();

        static::$logs = [];
    }

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->clearLogs();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->clearLogs();
    }
}
