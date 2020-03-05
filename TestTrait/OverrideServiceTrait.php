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

    /**
     * Executed before each test, when the service is instanciated
     *
     * @return void
     */
    public function setUp(): void
    {
        // Do nothing, override this function if needed.
    }

    /**
     * Executed after each test, when the service is destroyed
     */
    public function __destruct()
    {
        // Do nothing, override this function if needed.
    }
}
