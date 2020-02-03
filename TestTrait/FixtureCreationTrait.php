<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
/** @noinspection CallableParameterUseCaseInTypeContextInspection */
declare(strict_types=1);

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

        $property = $reflectionClass->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $value);

        return $object;
    }
}
