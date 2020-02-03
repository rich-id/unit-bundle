<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Stubs;

use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerStub
 *
 * @package   RichCongress\Bundle\UnitBundle\Stubs
 * @author    Matthias Devlamynck <mdevlamynck@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class LoggerStub implements LoggerInterface
{
    /**
     * @var array
     */
    protected $logEntries = [];

    /**
     * @return array Logs recorded.
     */
    public function getLogs(): array
    {
        return $this->logEntries;
    }

    /**
     * @return void
     */
    public function clearLogs(): void
    {
        $this->logEntries = [];
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = array()): void
    {
        $this->log('emergency', $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert($message, array $context = array()): void
    {
        $this->log('alert', $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical($message, array $context = array()): void
    {
        $this->log('critical', $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = array()): void
    {
        $this->log('error', $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning($message, array $context = array()): void
    {
        $this->log('warning', $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = array()): void
    {
        $this->log('notice', $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info($message, array $context = array()): void
    {
        $this->log('info', $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = array()): void
    {
        $this->log('debug', $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function log($level, $message, array $context = array()): void
    {
        $this->logEntries[] = [$level, $message, $context];
    }

    /**
     * @param $_
     *
     * @return self
     *
     * @codeCoverageIgnore
     */
    public function pushProcessor($_): self
    {
        return $this;
    }

    /**
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function popProcessor(): void
    {
    }
}
