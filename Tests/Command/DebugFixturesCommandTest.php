<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Command;

use RichCongress\Bundle\UnitBundle\Command\DebugFixturesCommand;
use RichCongress\Bundle\UnitBundle\TestCase\CommandTestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithFixtures;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;

/**
 * Class DebugFixturesCommandTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Command
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @WithFixtures
 * @covers \RichCongress\Bundle\UnitBundle\Command\DebugFixturesCommand
 */
class DebugFixturesCommandTest extends CommandTestCase
{
    /**
     * @var DebugFixturesCommand
     */
    protected $command;

    /**
     * @return void
     */
    public function beforeTest(): void
    {
        $this->command = $this->getContainer()->get(DebugFixturesCommand::class);
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        $output = $this->execute();

        self::assertStringContainsString(DummyEntity::class, $output);
        self::assertStringContainsString(User::class, $output);
        self::assertStringContainsString('entity_19', $output);
        self::assertStringContainsString('user_19', $output);
    }

    /**
     * @return void
     */
    public function testExecuteWithClass(): void
    {
        $output = $this->execute([
            '-c' => 'User',
        ]);

        self::assertStringNotContainsString(DummyEntity::class, $output);
        self::assertStringContainsString(User::class, $output);
        self::assertStringNotContainsString('entity_19', $output);
        self::assertStringContainsString('user_19', $output);
    }
}
