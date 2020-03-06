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
    protected $overrideService = [];

    /**
     * @param OverrideServiceInterface $overrideServiceClass
     *
     * @return void
     */
    public function addOverrideServiceClass(OverrideServiceInterface $overrideServiceClass): void
    {
        $this->overrideService[] = $overrideServiceClass;
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

        foreach ($this->overrideService as $overrideService) {
            $callback = [$overrideService, 'getOverridenServiceNames'];
            $overridenServices = $callback();
            $prefix = "\n   - ";

            $io->block(
                \get_class($overrideService) . $prefix . implode($prefix, $overridenServices),
                null,
                'fg=black;bg=blue',
                ' ',
                true
            );
        }

        return 0;
    }
}
