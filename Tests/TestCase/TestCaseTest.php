<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestCase;


use RichCongress\Bundle\UnitBundle\TestCase\TestCase;

/**
 * Class TestCase
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestCase\TestCase
 * @covers \RichCongress\Bundle\UnitBundle\TestTrait\CommonTestCaseTrait
 */
class TestCaseTest extends TestCase
{
    /**
     * @return void
     */
    public function testSetUp(): void
    {
        self::assertTrue($this->beforeTestExecuted);
    }

    /**
     * @return void
     */
    public function testTearDown(): void
    {
        self::assertFalse($this->afterTestExecuted);

        $this->tearDown();

        self::assertTrue($this->afterTestExecuted);
    }
}
