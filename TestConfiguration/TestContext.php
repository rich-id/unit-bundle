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
     * @var array
     */
    public static $envOverloads;

    /**
     * @var array
     */
    public static $paramConverterOverloads;

    /**
     * @param string $test
     *
     * @return void
     */
    public static function parseTest(string $test): void
    {
        $keyname = explode(' ', $test)[0];
        $data = explode('::', $keyname);

        if (\count($data) !== 2) {
            return;
        }

        [static::$testClass, static::$testName] = $data;

        static::$needContainer = TestConfigurationExtractor::doesTestNeedsContainer(static::$testClass, static::$testName);
        static::$needFixtures = TestConfigurationExtractor::doesTestNeedsFixtures(static::$testClass, static::$testName);
        static::$envOverloads = TestConfigurationExtractor::getEnvOverloads(static::$testClass, static::$testName);
        static::$paramConverterOverloads = TestConfigurationExtractor::getParamConverterOverloads(static::$testClass, static::$testName);
    }
}
