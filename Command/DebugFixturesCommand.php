<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Command;

use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DebugFixturesCommand
 *
 * @package   RichCongress\Bundle\UnitBundle\Command
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DebugFixturesCommand extends Command
{
    public static $defaultName = 'debug:fixtures';

    /**
     * @var FixturesManager
     */
    protected $fixturesManager;

    /**
     * DebugFixturesCommand constructor.
     *
     * @param FixturesManager|null $fixturesManager
     */
    public function __construct(FixturesManager $fixturesManager)
    {
        parent::__construct();

        $this->fixturesManager = $fixturesManager;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Prints out the list of fixtures per class.');
        $this->addOption('class', 'c', InputOption::VALUE_OPTIONAL, 'Class to display', '');
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
        $classToDisplay = $input->getOption('class');
        $classReferences = [];

        FixturesManager::$needFixturesLoading = true;
        FixturesManager::loadFixtures();

        foreach (FixturesManager::getReferences() as $reference => $value) {
            $class = \str_replace('Proxies\\__CG__\\', '', \get_class($value));
            $classReferences[$class] = $classReferences[$class] ?? [];

            $classReferences[$class][] = $reference;
        }

        foreach ($classReferences as $class => $references) {
            if (self::contains($class, $classToDisplay)) {
                $references = array_map(
                    static function (string $reference) {
                        return sprintf('   * %s', $reference);
                    },
                    $references
                );

                $io->block(
                    sprintf("%s\n\n%s", $class, implode("\n", $references)),
                    null,
                    'fg=black;bg=blue',
                    ' ',
                    true
                );
            }
        }

        return 0;
    }

    /**
     * @param string $class
     * @param string $classToFind
     *
     * @return boolean
     */
    protected static function contains(string $class, string $classToFind): bool
    {
        return $classToFind === '' || strpos($class, $classToFind) !== false;
    }
}
