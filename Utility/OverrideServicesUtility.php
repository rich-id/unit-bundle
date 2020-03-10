<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Utility;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class OverrideServicesUtility
 *
 * @package   RichCongress\Bundle\UnitBundle\Utility
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class OverrideServicesUtility
{
    /**
     * @var array|string[]
     */
    protected static $overrideServiceClasses = [];

    /**
     * @var array|null
     */
    protected static $cacheOverridenServicesIds;

    /**
     * @param string $class
     *
     * @return void
     */
    public static function addOverrideServiceClass(string $class): void
    {
        static::$overrideServiceClasses[] = $class;
    }

    /**
     * @return array|string[]
     */
    public function getOverridenServiceIds(): array
    {
        if (static::$cacheOverridenServicesIds === null) {
            static::$cacheOverridenServicesIds = [];

            foreach (static::$overrideServiceClasses as $class) {
                $callback = [$class, 'getOverridenServiceNames'];

                static::$cacheOverridenServicesIds = array_merge(
                    static::$cacheOverridenServicesIds,
                    $callback()
                );
            }
        }

        return static::$cacheOverridenServicesIds;
    }

    /**
     * @return void
     */
    public static function executeSetUps(): void
    {
        foreach (static::$overrideServiceClasses as $class) {
            $callback = [$class, 'setUp'];
            $callback();
        }
    }

    /**
     * @return void
     */
    public static function executeTearDowns(): void
    {
        foreach (static::$overrideServiceClasses as $class) {
            $callback = [$class, 'tearDown'];
            $callback();
        }
    }

    /**
     * @param ContainerInterface $container
     * @param string             $overridenService
     * @param                    $newService
     *
     * @return void
     *
     * @deprecated Use regular stubs instead of this function, which is very brutal
     */
    public static function overrideService(ContainerInterface $container, string $overridenService, $newService): void
    {
        try {
            $container->set($overridenService, $newService);
        } catch (\Exception $e) {
            try {
                // Force overriding
                $reflectionClass = new \ReflectionClass(\get_class($container));
                $property = $reflectionClass->getProperty('services');
                $property->setAccessible(true);
                $services = $property->getValue($container);
                $services[$overridenService] = $newService;
                $property->setValue($container, $services);
            } catch (\Exception $e) {
                throw new \RuntimeException(
                    sprintf('Impossible to override the service "%s".', $overridenService)
                );
            }
        }
    }
}
