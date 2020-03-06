<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Command;

use RichCongress\Bundle\UnitBundle\Command\DebugFixturesCommand;
use RichCongress\Bundle\UnitBundle\Command\DebugOverridenServicesCommand;
use RichCongress\Bundle\UnitBundle\Stubs\LoggerStub;
use RichCongress\Bundle\UnitBundle\TestCase\CommandTestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithFixtures;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;

/**
 * Class DebugOverridenServicesCommandTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Command
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @WithFixtures
 * @covers \RichCongress\Bundle\UnitBundle\Command\DebugOverridenServicesCommand
 */
class DebugOverridenServicesCommandTest extends CommandTestCase
{
    /**
     * @var DebugOverridenServicesCommand
     */
    protected $command;

    /**
     * @return void
     */
    public function beforeTest(): void
    {
        $this->command = $this->getContainer()->get(DebugOverridenServicesCommand::class);
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        $this->command->addOverrideServiceClass(DummyEntity::class);
        $output = $this->execute();

        self::assertStringNotContainsString(DummyEntity::class, $output);
        self::assertStringContainsString(LoggerStub::class, $output);
        self::assertStringContainsString('logger', $output);
    }
}
