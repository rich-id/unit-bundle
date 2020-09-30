<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestTrait;

use RichCongress\Bundle\UnitBundle\TestTrait\Assertion\Parameter;

/**
 * Trait MatchAssertionTrait
 *
 * @package   RichCongress\Bundle\UnitBundle\TestTrait
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
trait MatchAssertionTrait
{
    /**
     * @param array $expected
     * @param array $tested
     *
     * @return void
     */
    protected static function assertMatch(array $expected, array $tested): void
    {
        // The array key presences are tested in the first place
        foreach (array_keys($expected) as $expectedKey) {
            self::assertArrayHasKey($expectedKey, $tested);
        }

        // The content of each key is then tested
        foreach ($expected as $expectedKey => $expectedMatch) {
            $testedValue = $tested[$expectedKey];

            if (is_array($expectedMatch)) {
                static::assertMatch($expectedMatch, $testedValue);
            } else if ($expectedMatch instanceof Parameter) {
                static::assertMatchParameter($expectedMatch, $testedValue);
            } else if ($expectedMatch !== null) {
                static::assertEquals($expectedMatch, $testedValue);
            }
        }
    }

    /**
     * @param Parameter $parameter
     * @param mixed     $testedValue
     *
     * @return void
     */
    private static function assertMatchParameter(Parameter $parameter, $testedValue): void
    {
        static::assertTypeAndInstanceParameter($parameter, $testedValue);

        // Regex test
        if ($parameter->regex !== null) {
            self::assertRegExp($parameter->regex, $testedValue);
        }

        // Choice test
        if ($parameter->choice !== null) {
            self::assertContains($testedValue, $parameter->choice);
        }
    }

    /**
     * @param Parameter $parameter
     * @param mixed     $testedValue
     *
     * @return void
     */
    private static function assertTypeAndInstanceParameter(Parameter $parameter, $testedValue): void
    {
        if ($testedValue === null && $parameter->isNullable) {
            self::assertNull($testedValue);
            return;
        }

        // Type test
        switch ($parameter->type) {
            case 'string':
                self::assertIsString($testedValue);
                return;

            case 'integer':
                self::assertIsInt($testedValue);
                return;

            case 'float':
                self::assertIsFloat($testedValue);
                return;

            case 'array':
                self::assertIsArray($testedValue);
                return;

            case 'boolean':
                self::assertIsBool($testedValue);
                return;
        }

        if ($parameter->class !== null) {
            self::assertInstanceOf($parameter->class, $testedValue);
        }
    }
}
