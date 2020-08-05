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
    protected static function getFaker(): Faker\Generator
    {
        if (self::$faker === null) {
            self::$faker = Faker\Factory::create(static::FAKER_LANG);
        }

        return self::$faker;
    }

    /**
     * @return object
     */
    abstract protected static function makeDefault();

    /**
     * @param array $data
     *
     * @return object|mixed
     */
    public static function make(array $data = [])
    {
        return self::setValues(
            static::makeDefault(),
            $data
        );
    }

    /**
     * @param string $class
     * @param array  $data
     *
     * @return object|mixed
     */
    protected static function build(string $class, array $data)
    {
        return static::setValues(new $class(), $data);
    }

    /**
     * @param mixed $object
     * @param array $data
     *
     * @return object|mixed
     */
    protected static function setValues($object, array $data)
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
     * @return object|mixed
     */
    protected static function setValue($object, string $property, $value, \ReflectionClass $reflectionClass = null)
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
     * @param object|mixed $object
     * @param string       $dateAddPropertyName
     * @param string       $dateUpdatePropertyName
     *
     * @return object|mixed
     */
    protected static function setDateAddAndUpdate(
        $object,
        string $dateAddPropertyName = 'dateAdd',
        string $dateUpdatePropertyName = 'dateUpdate'
    ) {
        $dateAdd = self::getFaker()->dateTime;
        $dateUpdate = self::getFaker()->dateTimeBetween($dateAdd->format('Y-m-d H:i:s'));

        return self::setValues($object, [
            $dateAddPropertyName    => $dateAdd,
            $dateUpdatePropertyName => $dateUpdate,
        ]);
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
