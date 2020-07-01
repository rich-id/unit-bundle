<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\EntityProxy;

use Faker;

/**
 * Class AbstractEntityProxy
 *
 * @package   RichCongress\Bundle\UnitBundle\EntityProxy
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
abstract class AbstractEntityProxy implements EntityProxyInterface
{
    public const FAKER_LANG = 'fr_FR';

    /**
     * @var Faker\Generator|null
     */
    private static $faker;

    /**
     * @return Faker\Generator
     */
    public static function getFaker(): Faker\Generator
    {
        if (self::$faker === null) {
            self::$faker = Faker\Factory::create(static::FAKER_LANG);
        }

        return self::$faker;
    }

    /**
     * @param array $data
     *
     * @return object
     */
    public static function buildObject(array $data)
    {
        $object = static::makeDefault();

        return self::setValues($object, $data);
    }

    /**
     * @param mixed $object
     * @param array $data
     *
     * @return object
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
     * @return object
     */
    public static function setValue($object, string $property, $value, \ReflectionClass $reflectionClass = null)
    {
        if ($reflectionClass === null) {
            $reflectionClass = new \ReflectionClass(\get_class($object));
        }

        /** @var \ReflectionProperty $reflectionProperty */
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
        $tmpReflectionClass = $reflectionClass;

        while ($tmpReflectionClass instanceof \ReflectionClass) {
            if ($tmpReflectionClass->hasProperty($property)) {
                return $tmpReflectionClass->getProperty($property);
            }

            $tmpReflectionClass = $tmpReflectionClass->getParentClass();
        }

        throw new \LogicException(
            sprintf('The property "%s" does not exist for the entity %s', $property, $originalClass)
        );
    }
}
