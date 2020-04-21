<?php

namespace RichCongress\Bundle\UnitBundle\TestConfiguration;

use Doctrine\Common\Annotations\AnnotationReader;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\Env;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\AbstractOverloadAnnotation;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\ParameterBag;
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
        $testConfiguration = $annotation instanceof TestAnnotationInterface
            ? $annotation->getTestConfiguration()
            : new AnnotationConfiguration();

        $annotations = static::getAnnotationReader()->getMethodAnnotations($reflectionMethod);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Env) {
                $testConfiguration->envOverloads[$annotation->property] = static::getValueFromAnnotation($annotation);
                continue;
            }

            if ($annotation instanceof ParameterBag) {
                $testConfiguration->parameterBagOverloads[$annotation->property] = static::getValueFromAnnotation($annotation);
                continue;
            }
        }

        return $testConfiguration;
    }

    /**
     * @param AbstractOverloadAnnotation $annotation
     *
     * @return mixed
     */
    protected static function getValueFromAnnotation(AbstractOverloadAnnotation $annotation)
    {
        if ($annotation->value !== null) {
            return $annotation->value;
        }

        // Todo: Parse expression
        return $annotation->expr;
    }
}
