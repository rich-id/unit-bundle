<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestTrait\Assertion;

/**
 * Class Parameter
 *
 * @package   RichCongress\Bundle\UnitBundle\TestTrait\Assertion
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class Parameter
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $regex;

    /**
     * @var string
     */
    public $choice;

    /**
     * @return static
     */
    public static function string(): self
    {
        return static::createType('string');
    }

    /**
     * @return static
     */
    public static function integer(): self
    {
        return static::createType('integer');
    }

    /**
     * @return static
     */
    public static function float(): self
    {
        return static::createType('float');
    }

    /**
     * @return static
     */
    public static function array(): self
    {
        return static::createType('array');
    }

    /**
     * @return static
     */
    public static function boolean(): self
    {
        return static::createType('boolean');
    }

    /**
     * @param string $regex
     *
     * @return self
     */
    public static function regex(string $regex): self
    {
        $parameter = static::createType('string');
        $parameter->regex = $regex;

        return $parameter;
    }

    /**
     * @param array $choice
     *
     * @return self
     */
    public static function choice(array $choice): self
    {
        $parameter = new static();
        $parameter->choice = $choice;

        return $parameter;
    }

    /**
     * @param string $type
     *
     * @return static
     */
    protected static function createType(string $type): self
    {
        $parameter = new static();
        $parameter->type = $type;

        return $parameter;
    }
}
