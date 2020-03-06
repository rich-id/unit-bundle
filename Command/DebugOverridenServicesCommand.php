<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Command;

use RichCongress\Bundle\UnitBundle\OverrideService\OverrideServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DebugOverridenServicesCommand
 *
 * @package   RichCongress\Bundle\UnitBundle\Command
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DebugOverridenServicesCommand extends Command
{
    public static $defaultName = 'debug:overriden_services';

    /**
     * @var array|OverrideServiceInterface
     */
    protected $overrideServiceClasses = [];

    /**
     * @param string $overrideServiceClass
     *
     * @return void
     */
    public function addOverrideServiceClass(string $overrideServiceClass): void
    {
        if (!is_a($overrideServiceClass, OverrideServiceInterface::class, true)) {
            return;
        }

        $this->overrideServiceClasses[] = $overrideServiceClass;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Prints out all test services that override base services');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return integer|void
     *
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->overrideServiceClasses as $overrideServiceClass) {
            $callback = [$overrideServiceClass, 'getOverridenServiceNames'];
            $overridenServices = $callback();

            $io->block(
                sprintf("%s\n\n%s", $overrideServiceClass, implode("\n", $overridenServices)),
                null,
                'fg=black;bg=blue',
                ' ',
                true
            );
        }

        return 0;
    }
}
