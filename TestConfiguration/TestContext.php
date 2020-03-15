<?php

namespace RichCongress\Bundle\UnitBundle\TestConfiguration;

/**
 * Class TestContext
 *
 * @package RichCongress\Bundle\UnitBundle\AnnotationConfiguration
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class TestContext
{
    /**
     * @var string
     */
    public static $testClass;

    /**
     * @var string
     */
    public static $testName;

    /**
     * @var bool
     */
    public static $needContainer;

    /**
     * @var bool
     */
    public static $needFixtures;

    /**
     * @param string $test
     *
     * @return void
     */
    public static function parseTest(string $test): void
    {
        [static::$testClass, static::$testName] = explode('::', $test);

        static::$needContainer = TestConfigurationExtractor::doesTestNeedsContainer(static::$testClass, static::$testName);
        static::$needFixtures = TestConfigurationExtractor::doesTestNeedsFixtures(static::$testClass, static::$testName);
    }
}