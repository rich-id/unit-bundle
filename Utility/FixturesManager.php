<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Utility;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use RichCongress\Bundle\UnitBundle\Resources\Stub\KernelTestCaseStub;
use RichCongress\Bundle\UnitBundle\TextUi\Output;
use RichCongress\Bundle\UnitBundle\TextUi\Timer;

/**
 * Class FixturesManager
 *
 * @package   RichCongress\Bundle\UnitBundle\Utility
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class FixturesManager
{
    /**
     * @var DatabaseToolCollection
     */
    protected static $databaseToolCollection;

    /**
     * @var ReferenceRepository
     */
    protected static $fixtures;

    /**
     * @var array
     */
    protected static $fixturesClasses = [];

    /**
     * @var boolean
     */
    public static $needFixturesLoading = false;

    /**
     * FixturesManager constructor.
     *
     * @param DatabaseToolCollection $databaseToolCollection
     */
    public function __construct(DatabaseToolCollection $databaseToolCollection)
    {
        static::$databaseToolCollection = $databaseToolCollection;
    }

    /**
     * @param array $classes
     *
     * @return void
     */
    public static function setFixturesClasses(array $classes): void
    {
        static::$fixturesClasses = \array_keys($classes);
    }

    /**
     * @return void
     *
     * @throws \Throwable
     */
    public static function loadFixtures(): void
    {
        if (
            !static::$needFixturesLoading
            || static::$fixtures !== null
            || count(static::$fixturesClasses) === 0
        ) {
            return;
        }

        static::displayFixturesLoading();

        $kernelTestCase = new KernelTestCaseStub();
        $dbTool = static::$databaseToolCollection->get(
            null,
            'doctrine',
            ORMPurger::PURGE_MODE_DELETE,
            $kernelTestCase
        );

        try {
            static::$fixtures = $dbTool->loadFixtures(static::$fixturesClasses, false)->getReferenceRepository();
        } catch (\Throwable $e) {
            static::displayFixturesLoadingError($e);
        }

        static::displayFixturesLoaded();
    }

    /**
     * @param string $reference
     *
     * @return boolean
     */
    public static function hasReference(string $reference): bool
    {
        return static::$fixtures->hasReference($reference);
    }

    /**
     * @return array
     */
    public static function getReferences(): array
    {
        return static::$fixtures->getReferences();
    }

    /**
     * Get the object from the reference
     *
     * @param string              $reference
     *
     * @return mixed
     */
    public static function getReference(string $reference)
    {
        if (static::hasReference($reference)) {
            return static::$fixtures->getReference($reference);
        }

        $matchReference = null;
        $matchDistance = null;

        foreach (\array_keys(static::$fixtures->getReferences()) as $objectReference) {
            $distance = levenshtein($reference, $objectReference);

            if ($matchDistance === null || $distance < $matchDistance) {
                $matchDistance = $distance;
                $matchReference = $objectReference;
            }
        }

        throw new \OutOfBoundsException(
            sprintf('The reference "%s" does not exist. Did you mean "%s"?', $reference, $matchReference)
        );
    }

    /**
     * @param string $reference
     * @param        $object
     *
     * @return void
     */
    public static function setReference(string $reference, $object): void
    {
        static::$fixtures->setReference($reference, $object);
    }

    /**
     * @param string $reference
     *
     * @return mixed
     */
    public static function getIdentity(string $reference)
    {
        return static::$fixtures->getIdentities()[$reference] ?? null;
    }

    /**
     * @return void
     */
    protected static function displayFixturesLoading(): void
    {
        echo Output::GO_LINE_BEGINNING;
        echo Output::print('Database initialization...', Output::COLOR_WHITE, Output::BG_COLOR_CYAN);
        Timer::start();
    }

    /**
     * @return void
     */
    protected static function displayFixturesLoaded(): void
    {
        echo Output::GO_LINE_BEGINNING;
        echo Output::print(
            sprintf(
                '%s Database initialized! (%s)',
                Output::CHAR_CHECK,
                Timer::stopToString()
            ),
            Output::COLOR_BLACK,
            Output::BG_COLOR_GREEN
        );

        echo "\n\n";
    }

    /**
     * @param \Throwable $exception
     *
     * @return void
     *
     * @throws \Throwable
     */
    protected static function displayFixturesLoadingError(\Throwable $exception): void
    {
        echo OUTPUT::error("\nThe following error occured during the fixtures loading:\n");
        echo "\n";

        throw $exception;
    }
}
