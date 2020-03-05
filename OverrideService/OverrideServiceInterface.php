<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\OverrideService;

/**
 * Interface OverrideServiceInterface
 *
 * @package   RichCongress\Bundle\UnitBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
interface OverrideServiceInterface
{
    /**
     * @return array
     */
    public static function getOverridenServiceNames(): array;

    /**
     * Executed before each test, when the service is instanciated
     *
     * @return void
     */
    public function setUp(): void;

    /**
     * Executed after each test, when the service is destroyed
     *
     * @return void
     */
    public function __destruct();
}
