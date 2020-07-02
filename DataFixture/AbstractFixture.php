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
     * @var integer
     */
    protected $count = 0;

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
        $this->save($object, $references);

        return $object;
    }

    /**
     * @param string|array $references
     * @param array        $data
     *
     * @return object|mixed
     */
    protected function createFromDefault($references, array $data = [])
    {
        $object = $this->generateDefaultEntity();

        self::setValue($object, 'id', null);
        self::setValues($object, $data);
        $this->save($object, $references);

        return $object;
    }

    /**
     * @param string|object $base
     * @param string|array  $references
     * @param array         $data
     *
     * @return object|mixed
     */
    protected function createFrom($base, $references, array $data = [])
    {
        $object = \is_string($base)
            ? clone $this->getReference($base)
            : clone $base;

        self::setValue($object, 'id', null);
        self::setValues($object, $data);
        $this->save($object, $references);

        return $object;
    }

    /**
     * @return object|mixed
     */
    protected function generateDefaultEntity()
    {
        throw new \LogicException('You must override "generateDefaultEntity" to generate the default entity.');
    }

    /**
     * @param object       $object
     * @param string|array $references
     *
     * @return void
     */
    protected function save($object, $references = []): void
    {
        foreach ((array) $references as $reference) {
            $this->setReference($reference, $object);
        }

        if ($this->manager !== null) {
            $this->manager->persist($object);
            $this->count++;
        }
    }

    /**
     * @param string                $class
     * @param array|string|string[] $keynames
     * @param string                $referencePrefix
     *
     * @return void
     */
    protected function saveForKeynames($class, $keynames, string $referencePrefix): void
    {
        foreach ((array) $keynames as $keyname) {
            $object = self::buildObject($class, ['keyname' => $keyname]);
            $this->save($object, $referencePrefix . $keyname);
        }
    }
}
