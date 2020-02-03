<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestConfiguration\Annotation;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithFixtures;

/**
 * Class WithFixturesTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestConfiguration\Annotation
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithFixtures
 */
class WithFixturesTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetTestConfiguration(): void
    {
        $annotation = new WithFixtures();
        $config = $annotation->getTestConfiguration();

        self::assertTrue($config->withContainer);
        self::assertTrue($config->withFixtures);
    }
}
