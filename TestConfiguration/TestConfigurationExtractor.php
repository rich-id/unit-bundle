<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestConfiguration;

use RichCongress\Bundle\UnitBundle\Exception\MethodNotFoundException;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\TestAnnotationInterface;

/**
 * Class TestConfigurationExtractor
 *
 * @package   RichCongress\Bundle\UnitBundle\AnnotationConfiguration
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class TestConfigurationExtractor
{
    /**
     * Array of tests depencendies
     * [
     *     'class' => [
     *         'testName' => <AnnotationConfiguration>,
     *     ]
     * ]
     *
     * @var array
     */
    protected static $testConfigurations = [];

    /**
     * Array of classes depencendies
     * [
     *     'class' => <AnnotationConfiguration>,
     * ]
     *
     * @var array|AnnotationConfiguration[]
     */
    protected static $classConfigurations = [];

    /**
     * @param string $class
     *
     * @return void
     */
    public static function register(string $class): void
    {
        if (array_key_exists($class, static::$classConfigurations)) {
            return;
        }

        $reflectionClass = new \ReflectionClass($class);
        static::$classConfigurations[$class] = self::parseClassConfiguration($reflectionClass);
        static::$testConfigurations[$class] = [];

        foreach ($reflectionClass->getMethods(\ReflectionProperty::IS_PUBLIC) as $reflectionMethod) {
            static::$testConfigurations[$class][$reflectionMethod->getName()] = self::parseTestConfiguration($reflectionMethod);
        }
    }

    /**
     * @return bool
     */
    public static function doesContextNeedsFixtures(): bool
    {
        foreach (static::$classConfigurations as $class => $config) {
            if (static::doesClassNeedsFixtures($class)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $class
     *
     * @return boolean
     */
    public static function doesClassNeedsContainer(string $class): bool
    {
        static::check($class);

        /** @var AnnotationConfiguration $classConfiguration */
        $classConfiguration = static::$classConfigurations[$class];

        if ($classConfiguration->withContainer) {
            return true;
        }

        $classTestsConfigurations = static::$testConfigurations[$class];

        /** @var AnnotationConfiguration $config */
        foreach ($classTestsConfigurations as $method => $config) {
            if ($config->withContainer) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $class
     *
     * @return boolean
     */
    public static function doesClassNeedsFixtures(string $class): bool
    {
        static::check($class);

        /** @var AnnotationConfiguration $classConfiguration */
        $classConfiguration = static::$classConfigurations[$class];

        if ($classConfiguration->withFixtures) {
            return true;
        }

        $classTestsConfigurations = static::$testConfigurations[$class];

        /** @var AnnotationConfiguration $config */
        foreach ($classTestsConfigurations as $method => $config) {
            if ($config->withFixtures) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return boolean
     */
    public static function doesTestNeedsContainer(string $class, string $method): bool
    {
        static::check($class, $method);

        $classConfiguration = static::$classConfigurations[$class];
        $testConfiguration = static::$testConfigurations[$class][$method];

        return $classConfiguration->withContainer || $testConfiguration->withContainer;
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return boolean
     */
    public static function doesTestNeedsFixtures(string $class, ?string $method): bool
    {
        static::check($class, $method);

        $classConfiguration = static::$classConfigurations[$class];
        $testConfiguration = static::$testConfigurations[$class][$method];

        return $classConfiguration->withFixtures || $testConfiguration->withFixtures;
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return array
     */
    public static function getEnvOverloads(string $class, ?string $method): array
    {
        static::check($class, $method);

        $classConfiguration = static::$classConfigurations[$class];
        $testConfiguration = static::$testConfigurations[$class][$method];

        return array_unique(
            array_merge(
                $testConfiguration->envOverloads,
                $classConfiguration->envOverloads
            )
        );
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return array
     */
    public static function getParamConverterOverloads(string $class, ?string $method): array
    {
        static::check($class, $method);

        $classConfiguration = static::$classConfigurations[$class];
        $testConfiguration = static::$testConfigurations[$class][$method];

        return array_unique(
            array_merge(
                $testConfiguration->envOverloads,
                $classConfiguration->envOverloads
            )
        );
    }

    /**
     * @param \ReflectionClass $reflectionClass
     *
     * @return AnnotationConfiguration
     */
    protected static function parseClassConfiguration(\ReflectionClass $reflectionClass): AnnotationConfiguration
    {
        $configuration = ConfigurationAnnotationReader::getClassConfiguration($reflectionClass, TestAnnotationInterface::class);

        if ($configuration === null) {
            return new AnnotationConfiguration();
        }

        return $configuration;
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return AnnotationConfiguration
     */
    protected static function parseTestConfiguration(\ReflectionMethod $reflectionMethod): AnnotationConfiguration
    {
        $configuration = ConfigurationAnnotationReader::getMethodConfiguration($reflectionMethod, TestAnnotationInterface::class);

        if ($configuration === null) {
            return new AnnotationConfiguration();
        }

        return $configuration;
    }

    /**
     * @param string      $class
     * @param string|null $method
     *
     * @return void
     */
    protected static function check(string $class, string $method = null): void
    {
        // Not parsed yet
        if (!array_key_exists($class, static::$classConfigurations)) {
            static::register($class);
        }

        MethodNotFoundException::checkAndThrow(static::$testConfigurations, $class, $method);
    }
}
