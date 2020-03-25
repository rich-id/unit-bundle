<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestTrait;

use RichCongress\Bundle\UnitBundle\TestCase\TestCase;
use RichCongress\Bundle\UnitBundle\TestTrait\Assertion\Parameter;

/**
 * Class MatchAssertionTraitTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestTrait
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestTrait\MatchAssertionTrait
 * @covers \RichCongress\Bundle\UnitBundle\TestTrait\Assertion\Parameter
 */
class MatchAssertionTraitTest extends TestCase
{
    /**
     * @return void
     */
    public function testAssertMatch(): void
    {
        $tested = [
            'id'          => 3,
            'name'        => 'Any Name',
            'size'        => 1,
            'floatValue'  => 3.1,
            'arrayValue'  => ['yes'],
            'isBoolean'   => true,
            'siret'       => '012345678910',
            'choiceValue' => 'Certain Type',
        ];

        $expected = [
            'id'          => 3,
            'name'        => Parameter::string(),
            'size'        => Parameter::integer(),
            'floatValue'  => Parameter::float(),
            'arrayValue'  => Parameter::array(),
            'isBoolean'   => Parameter::boolean(),
            'siret'       => Parameter::regex('/\d{12}/'),
            'choiceValue' => Parameter::choice(['Certain Type', 'Other Type']),
        ];

        self::assertMatch($expected, $tested);
    }
}
