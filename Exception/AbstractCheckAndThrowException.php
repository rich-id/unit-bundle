<?php

namespace RichCongress\Bundle\UnitBundle\Exception;

/**
 * Class AbstractCheckAndThrowException
 *
 * @package RichCongress\Bundle\UnitBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class AbstractCheckAndThrowException extends \LogicException
{
    /**
     * @var string|null
     */
    protected static $documentation = 'https://github.com/richcongress/unit-bundle/blob/v2.0.0/Docs/Exceptions.md';

    /**
     * @var string|null
     */
    protected static $error = '';

    /**
     * AbstractCheckAndThrowException constructor.
     */
    protected function __construct()
    {
        $message = static::$error;

        if (static::$documentation !== null) {
            $message .= "\nCheck the documentation: " . static::$documentation;

            if (static::$documentation === self::$documentation) {
                $explodedClass = explode('\\', static::class);
                $message .= '#' . end($explodedClass);
            }
        }

        parent::__construct($message);
    }

    /**
     * @param mixed ...$parameters
     *
     * @return void
     */
    public static function checkAndThrow(...$parameters): void
    {
        if (!static::check(...$parameters)) {
            throw new static();
        }
    }
}
