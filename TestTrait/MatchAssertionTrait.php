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
     * @param array  $expected
     * @param array  $tested
     *
     * @param string $previousIndex
     *
     * @return void
     */
    protected static function assertMatch(array $expected, array $tested, string $previousIndex = ''): void
    {
        // The array key presences are tested in the first place
        foreach (array_keys($expected) as $expectedKey) {
            self::assertArrayHasKey($expectedKey, $tested);
        }

        // The content of each key is then tested
        foreach ($expected as $expectedKey => $expectedMatch) {
            $testedValue = $tested[$expectedKey];
            $totalIndex = $previousIndex !== null ? $previousIndex . '/' . $testedValue : (string) $testedValue;

            if (is_array($expectedMatch)) {
                static::assertMatch($expectedMatch, $testedValue, $totalIndex);
            } else if ($expectedMatch instanceof Parameter) {
                static::assertMatchParameter($expectedMatch, $testedValue, $totalIndex);
            } else if ($expectedMatch !== null) {
                static::assertEquals($expectedMatch, $testedValue);
            }
        }
    }

    /**
     * @param Parameter   $parameter
     * @param mixed       $testedValue
     * @param string|null $index
     *
     * @return void
     */
    private static function assertMatchParameter(Parameter $parameter, $testedValue, string $index = null): void
    {
        static::assertTypeAndInstanceParameter($parameter, $testedValue, $index);

        // Regex test
        if ($parameter->regex !== null) {
            self::assertRegExp($parameter->regex, $testedValue, static::getError($index));
        }

        // Choice test
        if ($parameter->choice !== null) {
            self::assertContains($testedValue, $parameter->choice, static::getError($index));
        }
    }

    /**
     * @param Parameter   $parameter
     * @param mixed       $testedValue
     * @param string|null $index
     *
     * @return void
     */
    private static function assertTypeAndInstanceParameter(Parameter $parameter, $testedValue, string $index = null): void
    {
        if ($testedValue === null && $parameter->isNullable) {
            self::assertNull($testedValue, static::getError($index));
            return;
        }

        // Type test
        switch ($parameter->type) {
            case 'string':
                self::assertIsString($testedValue, static::getError($index));
                return;

            case 'integer':
                self::assertIsInt($testedValue, static::getError($index));
                return;

            case 'float':
                self::assertIsFloat($testedValue, static::getError($index));
                return;

            case 'array':
                self::assertIsArray($testedValue, static::getError($index));
                return;

            case 'boolean':
                self::assertIsBool($testedValue, static::getError($index));
                return;
        }

        if ($parameter->class !== null) {
            self::assertInstanceOf($parameter->class, $testedValue, static::getError($index));
        }
    }

    /**
     * @param string|null $index
     *
     * @return string
     */
    private static function getError(string $index = null): string
    {
        return $index !== null
            ? sprintf('Error during the match assertion for the index "%s".', $index)
            : 'Error during the match assertion.';
    }
}
