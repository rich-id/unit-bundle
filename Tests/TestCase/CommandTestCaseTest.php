<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestCase;

use RichCongress\Bundle\UnitBundle\TestCase\CommandTestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithContainer;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Command\DummyCommand;
use Symfony\Component\Console\Command\Command;

/**
 * Class CommandTestCasesTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestCase\CommandTestCase
 */
class CommandTestCaseTest extends CommandTestCase
{
    /**
     * @return void
     */
    public function beforeTest(): void
    {
        $this->command = new DummyCommand();
    }

    /**
     * @return void
     */
    public function testExecuteWithoutContainer(): void
    {
        $output = $this->execute();

        self::assertStringContainsString('DummyCommand', $output);
    }

    /**
     * @WithContainer
     *
     * @return void
     */
    public function testExecuteWithContainer(): void
    {
        $output = $this->execute();

        self::assertStringContainsString('DummyCommand', $output);
    }
}
