<?php

namespace RichCongress\Bundle\UnitBundle\Exception;

/**
 * Class MethodNotFoundException
 *
 * @package RichCongress\Bundle\UnitBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class MethodNotFoundException extends \LogicException
{
    /**
     * @var string
     */
    protected $message = 'The method "%s" does not exist within the class "%s"';

    /**
     * @param array       $testConfigurations
     * @param string      $class
     * @param string|null $method
     *
     * @return void
     * @throws MethodNotFoundException
     */
    public static function checkAndThrow(array $testConfigurations, string $class, ?string $method): void
    {
        if ($method !== null && !array_key_exists($method, $testConfigurations[$class])) {
            $exception = new self();
            $exception->message = sprintf($exception->message, $method, $class);

            throw $exception;
        }
    }
}
