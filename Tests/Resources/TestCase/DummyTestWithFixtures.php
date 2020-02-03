<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase;

use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithFixtures;

/**
 * Class DummyTestWithFixtures
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DummyTestWithFixtures
{
    /**
     * @WithFixtures
     *
     * @return void
     */
    public function dummyFunction(): void
    {
        // Do nothing
    }
}
