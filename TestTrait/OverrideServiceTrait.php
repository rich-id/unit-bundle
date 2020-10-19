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
    /** @var object */
    protected $innerService;

    /**
     * @return array
     */
    public static function getOverridenServiceNames(): array
    {
        return isset(static::$overridenServices)
            ? (array) static::$overridenServices
            : [];
    }

    public function setInnerService($service): void
    {
        $this->innerService = $service;
    }

    public function __call(string $method, array $arguments)
    {
        return $this->innerService->$method(...$arguments);
    }

    /**
     * @return void
     */
    public static function setUp(): void
    {
        // Override to use this function
    }

    /**
     * @return void
     */
    public static function tearDown(): void
    {
        // Override to use this function
    }
}
