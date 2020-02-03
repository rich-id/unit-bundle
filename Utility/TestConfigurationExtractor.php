<?php /** @noinspection PhpDocMissingThrowsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Utility;

use Doctrine\Common\Annotations\AnnotationReader;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\TestAnnotationInterface;
use RichCongress\Bundle\UnitBundle\TestConfiguration\TestConfiguration;

/**
 * Class TestConfigurationExtractor
 *
 * @package   RichCongress\Bundle\UnitBundle\Utility
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class TestConfigurationExtractor
{
    /**
     * Array of tests depencendies
     * [
     *     'class' => [
     *         'testName' => <TestConfiguration>,
     *     ]
     * ]
     *
     * @var array
     */
    protected static $testsConfiguration = [];

    /**
     * Array of classes depencendies
     * [
     *     'class' => <TestConfiguration>,
     * ]
     *
     * @var array
     */
    protected static $classesConfiguration = [];

    /**
     * @var AnnotationReader
     */
    protected static $annotationReader;

    /**
     * @param string $class
     *
     * @return boolean
     */
    public static function doesClassNeedsContainer(string $class): bool
    {
        /** @var TestConfiguration $classConfiguration */
        $classConfiguration  = static::getClassConfiguration($class);

        if ($classConfiguration->withContainer) {
            return true;
        }

        $classTestsConfigurations = static::getClassTestsConfiguration($class);

        /** @var TestConfiguration $config */
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
        /** @var TestConfiguration $classConfiguration */
        $classConfiguration  = static::getClassConfiguration($class);

        if ($classConfiguration->withFixtures) {
            return true;
        }

        $classTestsConfiguration = static::getClassTestsConfiguration($class);

        /** @var TestConfiguration $config */
        foreach ($classTestsConfiguration as $method => $config) {
            if ($config->withFixtures) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string      $class
     * @param string|null $method
     *
     * @return boolean
     */
    public static function doesTestNeedsContainer(string $class, ?string $method): bool
    {
        $classConfiguration = static::getClassConfiguration($class);

        if ($classConfiguration->withContainer) {
            return true;
        }

        return $method !== null && static::getTestConfiguration($class, $method)->withContainer;
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return boolean
     */
    public static function doesTestNeedsFixtures(string $class, ?string $method): bool
    {
        $classConfiguration = static::getClassConfiguration($class);

        if ($classConfiguration->withFixtures) {
            return true;
        }

        return $method !== null && static::getTestConfiguration($class, $method)->withFixtures;
    }

    /**
     * @param string $class
     *
     * @return void
     */
    protected static function parseClassAndMethods(string $class): void
    {
        $classTestsDependencies = [];
        $reflectionClass = new \ReflectionClass($class);
        static::$classesConfiguration[$class] = static::parseClassConfiguration($reflectionClass);

        foreach ($reflectionClass->getMethods(\ReflectionProperty::IS_PUBLIC) as $reflectionMethod) {
            $classTestsDependencies[$reflectionMethod->getName()] = self::parseTestConfiguration($reflectionMethod);
        }

        static::$testsConfiguration[$class] = $classTestsDependencies;
    }

    /**
     * @param string $class
     *
     * @return TestConfiguration
     */
    protected static function getClassConfiguration(string $class): TestConfiguration
    {
        if (!isset(static::$classesConfiguration[$class])) {
            static::parseClassAndMethods($class);
        }

        return static::$classesConfiguration[$class];
    }

    /**
     * @param string $class
     *
     * @return array
     */
    protected static function getClassTestsConfiguration(string $class): array
    {
        return static::$testsConfiguration[$class];
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return TestConfiguration
     */
    protected static function getTestConfiguration(string $class, string $method): TestConfiguration
    {
        $classDependencies = static::getClassTestsConfiguration($class);

        if (!isset($classDependencies[$method])) {
            throw new \LogicException(
                sprintf('The method "%s" has not been parsed. Something went wrong.', $method)
            );
        }

        return $classDependencies[$method];
    }

    /**
     * @param \ReflectionClass $reflectionClass
     *
     * @return TestConfiguration
     */
    protected static function parseClassConfiguration(\ReflectionClass $reflectionClass): TestConfiguration
    {
        $annotation = static::getAnnotationReader()->getClassAnnotation($reflectionClass, TestAnnotationInterface::class);

        if ($annotation === null) {
            return new TestConfiguration();
        }

        return $annotation->getTestConfiguration();
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return TestConfiguration
     */
    protected static function parseTestConfiguration(\ReflectionMethod $reflectionMethod): TestConfiguration
    {
        $annotation = static::getAnnotationReader()->getMethodAnnotation($reflectionMethod, TestAnnotationInterface::class);

        if ($annotation === null) {
            return new TestConfiguration();
        }

        return $annotation->getTestConfiguration();
    }

    /**
     * @return AnnotationReader
     */
    protected static function getAnnotationReader(): AnnotationReader
    {
        if (static::$annotationReader === null) {
            AnnotationReader::addGlobalIgnoredName('dataProvider');
            AnnotationReader::addGlobalIgnoredName('covers');
            static::$annotationReader = new AnnotationReader();
        }

        return static::$annotationReader;
    }
}
