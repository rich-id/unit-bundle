<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase;

use RichCongress\Bundle\UnitBundle\TestCase\Internal\FixturesTestCase;
use RichCongress\Bundle\UnitBundle\TestTrait\FixtureCreationTrait;
use RichCongress\Bundle\UnitBundle\TestTrait\MatchAssertionTrait;

/**
 * Class TestCase
 *
 * @package   RichCongress\Bundle\UnitBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class TestCase extends FixturesTestCase
{
    use FixtureCreationTrait;
    use MatchAssertionTrait;
}
