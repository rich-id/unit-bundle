<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestTrait;

/**
 * Trait FixtureCreationTrait
 *
 * @package RichCongress\Bundle\UnitBundle\TestTrait
 */
trait FixtureCreationTrait
{
    /**
     * @param string $class
     * @param array $data
     *
     * @return mixed
     */
    public static function buildObject(string $class, array $data)
    {
        $object = new $class();

        return self::setValues($object, $data);
    }

    /**
     * @param mixed $object
     * @param array $data
     *
     * @return mixed
     */
    public static function setValues($object, array $data)
    {
        $reflectionClass = new \ReflectionClass(\get_class($object));

        foreach ($data as $property => $value) {
            self::setValue($object, $property, $value, $reflectionClass);
        }

        return $object;
    }

    /**
     * @param mixed                 $object
     * @param string                $property
     * @param mixed                 $value
     * @param \ReflectionClass|null $reflectionClass
     *
     * @return mixed
     */
    public static function setValue($object, string $property, $value, \ReflectionClass $reflectionClass = null)
    {
        if ($reflectionClass === null) {
            $reflectionClass = new \ReflectionClass(\get_class($object));
        }

        $reflectionProperty = static::getProperty($reflectionClass, $property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);

        return $object;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param string           $property
     *
     * @return \ReflectionProperty|null
     */
    private static function getProperty(\ReflectionClass $reflectionClass, string $property): ?\ReflectionProperty
    {
        $originalClass = $reflectionClass->getName();

        while ($reflectionClass instanceof \ReflectionClass) {
            if ($reflectionClass->hasProperty($property)) {
                return $reflectionClass->getProperty($property);
            }

            $reflectionClass = $reflectionClass->getParentClass();
        }

        throw new \LogicException(
            sprintf('The property "%s" does not exist for the entity %s', $property, $originalClass)
        );
    }
}
