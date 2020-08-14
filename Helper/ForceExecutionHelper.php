<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Helper;

/**
 * Class ForceExecutionHelper
 *
 * @package   RichCongress\Bundle\UnitBundle\Helper
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ForceExecutionHelper
{
    /**
     * ForceExecutionHelper constructor.
     */
    private function __construct()
    {
        // Block the instanciation
    }

    /**
     * @param object|string $object
     * @param string        $method
     * @param array|mixed   $args
     *
     * @return mixed
     */
    public static function executeMethod($object, string $method, $args = [])
    {
        if (!is_object($object) && !is_string($object)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The first argument must be an object or a class name, %s given.',
                    \gettype($object)
                )
            );
        }

        $reflectionClass = new \ReflectionClass($object);
        $reflectionMethod = $reflectionClass->getMethod($method);

        if (!\is_object($object) && !$reflectionMethod->isStatic()) {
            throw new \LogicException(
                'Cannot call a non static function without the instanciated object for ' . $reflectionClass->getName()
            );
        }

        $reflectionMethod->setAccessible(true);
        $value = $reflectionMethod->invokeArgs(
            $reflectionMethod->isStatic() ? $reflectionClass->getName() : $object,
            is_array($args) ? $args : [$args]
        );
        $reflectionMethod->setAccessible(false);

        return $value;
    }

    /**
     * @param object|string $object
     * @param string        $propertyName
     * @param mixed         $value
     *
     * @return void
     */
    public static function setValue($object, string $propertyName, $value): void
    {
        if (!is_object($object) && !is_string($object)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The first argument must be an object or a class name, %s given.',
                    \gettype($object)
                )
            );
        }

        $reflectionClass = new \ReflectionClass($object);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);

        if (!\is_object($object) && !$reflectionProperty->isStatic()) {
            throw new \LogicException(
                'Cannot call a non static function without the instanciated object for ' . $reflectionClass->getName()
            );
        }

        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(
            $reflectionProperty->isStatic() ? $reflectionClass->getName() : $object,
            $value
        );
        $reflectionProperty->setAccessible(false);
    }
}
