<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestTrait;

/**
 * Trait SubSetAssertionTrait
 *
 * @package   RichCongress\Bundle\UnitBundle\TestTrait
 * @author    Matthias Devlamynck <mdevlamynck@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
trait SubSetAssertionTrait
{
    /**
     * @param array $expected
     * @param array $tested
     * @param bool  $strictEquality
     *
     * @return void
     */
    protected static function assertSubSet(array $expected, array $tested, bool $strictEquality = false): void
    {
        foreach ($expected as $expectedValue) {
            static::assertContains($expectedValue, $tested, '', false, $strictEquality, $strictEquality);
        }
    }
}
