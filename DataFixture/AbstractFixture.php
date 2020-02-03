<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DataFixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use RichCongress\Bundle\UnitBundle\TestTrait\FixtureCreationTrait;

/**
 * Class AbstractFixture
 *
 * @package   RichCongress\Bundle\UnitBundle\DataFixture
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
abstract class AbstractFixture extends Fixture implements DataFixtureInterface
{
    use FixtureCreationTrait;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Loads the fixtures. All persisted data will be flushed at the end of the function.
     *
     * The recommanded way to create an object is to use the following function :
     *  $this->createobject('reference-1', Object::class, ['property' => 'value'])
     *
     * @return void
     */
    abstract protected function loadFixtures(): void;

    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadFixtures();

        $manager->flush();
    }

    /**
     * @param string|array $references
     * @param string       $class
     * @param array        $data
     *
     * @return object
     */
    protected function createObject($references, string $class, array $data)
    {
        $object = self::buildObject($class, $data);

        foreach ((array) $references as $reference) {
            $this->setReference($reference, $object);
        }

        if ($this->manager !== null) {
            $this->manager->persist($object);
        }

        return $object;
    }
}
