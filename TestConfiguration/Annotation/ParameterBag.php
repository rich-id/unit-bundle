<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation;

/**
 * Class ParameterBag
 *
 * @package   RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @Annotation
 */
class ParameterBag
{
    /**
     * @var string
     */
    public $property;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $expr;
}
