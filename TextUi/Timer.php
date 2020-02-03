<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TextUi;

/**
 * Class Timer
 *
 * @package   RichCongress\Bundle\UnitBundle\TextUi
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class Timer
{
    /**
     * @var float
     */
    private static $time;

    /**
     * @return void
     */
    public static function start(): void
    {
        self::$time = microtime(true);
    }

    /**
     * @return float
     */
    public static function stop(): float
    {
        if (self::$time === null) {
            throw new \LogicException('The timer is not started');
        }

        $elapsedTime = microtime(true) - self::$time;
        self::$time = null;

        return $elapsedTime;
    }

    /**
     * @return string
     */
    public static function stopToString(): string
    {
        $time = self::stop();

        if ($time >= 1) {
            return sprintf('%.2f', $time) . 's';
        }

        return sprintf('%d', $time * 1000) . 'ms';
    }
}
