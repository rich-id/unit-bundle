<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TextUi;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\TextUi\Output;

/**
 * Class OutputTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TextUi
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TextUi\Output
 */
class OutputTest extends TestCase
{
    /**
     * @return void
     */
    public function testSuccess(): void
    {
        $output = Output::success('Rutabaga');

        self::assertStringContainsString('Rutabaga', $output);
    }

    /**
     * @return void
     */
    public function testWarning(): void
    {
        $output = Output::warning('Rutabaga');

        self::assertStringContainsString('Rutabaga', $output);
    }

    /**
     * @return void
     */
    public function testDanger(): void
    {
        $output = Output::danger('Rutabaga');

        self::assertStringContainsString('Rutabaga', $output);
    }

    /**
     * @return void
     */
    public function testError(): void
    {
        $output = Output::error('Rutabaga');

        self::assertStringContainsString('Rutabaga', $output);
    }

    /**
     * @return void
     */
    public function testInfo(): void
    {
        $output = Output::info('Rutabaga');

        self::assertStringContainsString('Rutabaga', $output);
    }

    /**
     * @return void
     */
    public function testPrintWithBackground(): void
    {
        $output = Output::print('Rutabaga', Output::COLOR_BLACK, Output::BG_COLOR_CYAN);

        self::assertStringContainsString('Rutabaga', $output);
    }
}
