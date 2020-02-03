<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class CommandTestCase
 *
 * @package   RichCongress\Bundle\UnitBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class CommandTestCase extends TestCase
{
    /**
     * @var CommandTester
     */
    protected $commandTester;

    /**
     * @var Command
     */
    protected $command;

    /**
     * @internal Use beforeTest instead
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if ($this->command !== null) {
            $this->commandTester = new CommandTester($this->command);
        }
    }

    /**
     * @param array $input
     * @param array $options
     *
     * @return string
     */
    public function execute(array $input = [], array $options = []): string
    {
        $this->commandTester->execute($input, $options);

        return $this->commandTester->getDisplay();
    }
}
