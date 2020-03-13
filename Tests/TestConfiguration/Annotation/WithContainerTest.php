<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestConfiguration\Annotation;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithContainer;

/**
 * Class WithContainerTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\AnnotationConfiguration\Annotation
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithContainer
 */
class WithContainerTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetTestConfiguration(): void
    {
        $annotation = new WithContainer();
        $config = $annotation->getTestConfiguration();

        self::assertTrue($config->withContainer);
        self::assertFalse($config->withFixtures);
    }
}
