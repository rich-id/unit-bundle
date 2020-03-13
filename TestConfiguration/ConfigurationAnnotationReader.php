<?php

namespace RichCongress\Bundle\UnitBundle\TestConfiguration;

use Doctrine\Common\Annotations\AnnotationReader;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\TestAnnotationInterface;

/**
 * Class ConfigurationAnnotationReader
 *
 * @package RichCongress\Bundle\UnitBundle\TestConfiguration
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class ConfigurationAnnotationReader
{
    /**
     * @var AnnotationReader|null
     */
    protected static $annotationReader;

    /**
     * @return AnnotationReader
     */
    protected static function getAnnotationReader(): AnnotationReader
    {
        if (static::$annotationReader === null) {
            AnnotationReader::addGlobalIgnoredName('dataProvider');
            AnnotationReader::addGlobalIgnoredName('covers');
            static::$annotationReader = new AnnotationReader();
        }

        return static::$annotationReader;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param string           $annotationClass
     *
     * @return AnnotationConfiguration|null
     */
    public static function getClassConfiguration(\ReflectionClass $reflectionClass, string $annotationClass): ?AnnotationConfiguration
    {
        $annotation = static::getAnnotationReader()->getClassAnnotation($reflectionClass, $annotationClass);

        if ($annotation instanceof TestAnnotationInterface) {
            return $annotation->getTestConfiguration();
        }

        return null;
    }


    /**
     * @param \ReflectionMethod $reflectionMethod
     * @param string            $annotationClass
     *
     * @return AnnotationConfiguration|null
     */
    public static function getMethodConfiguration(\ReflectionMethod $reflectionMethod, string $annotationClass): ?AnnotationConfiguration
    {
        $annotation = static::getAnnotationReader()->getMethodAnnotation($reflectionMethod, $annotationClass);

        if ($annotation instanceof TestAnnotationInterface) {
            return $annotation->getTestConfiguration();
        }

        return null;
    }
}
