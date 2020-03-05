<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\OverrideService;

use RichCongress\Bundle\UnitBundle\Stubs\LoggerStub as BaseLoggerStub;
use RichCongress\Bundle\UnitBundle\TestTrait\OverrideServiceTrait;

/**
 * Class LoggerStub
 *
 * @package   RichCongress\Bundle\UnitBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class LoggerStub extends BaseLoggerStub implements OverrideServiceInterface
{
    public static $overridenServices = 'logger';

    use OverrideServiceTrait;

    /**
     * @var array
     */
    public static $logs;

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
     * @return void
     */
    public function __destruct()
    {
        $this->clearLogs();
    }
}
