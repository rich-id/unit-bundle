<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestTrait;

/**
 * Trait OverrideServiceTrait
 *
 * @package   RichCongress\Bundle\UnitBundle\TestTrait
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
trait OverrideServiceTrait
{
    /**
     * @return array
     */
    public static function getOverridenServiceNames(): array
    {
        return isset(static::$overridenServices)
            ? (array) static::$overridenServices
            : [];
    }
}
