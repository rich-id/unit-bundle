<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\Proxy;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;

/**
 * Class FixturesManagerProxy
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\Proxy
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class FixturesManagerProxy extends FixturesManager
{
    /**
     * @var DatabaseToolCollection
     */
    public static $databaseToolCollection;

    /**
     * @var ReferenceRepository
     */
    public static $fixtures;

    /**
     * @var array
     */
    public static $fixturesClasses;

    /**
     * @var boolean
     */
    public static $needFixturesLoading;

    /**
     * FixturesManagerProxy constructor.
     *
     * @param DatabaseToolCollection $databaseToolCollection
     */
    public function __construct(DatabaseToolCollection $databaseToolCollection)
    {
        static::$databaseToolCollection = null;
        static::$fixtures = null;
        static::$fixturesClasses = [];
        static::$needFixturesLoading = true;

        parent::__construct($databaseToolCollection);
    }
}
