<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestConfiguration;

use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\Env;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\ParameterBag;

/**
 * Class AnnotationConfiguration
 *
 * @package   RichCongress\Bundle\UnitBundle\AnnotationConfiguration
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class AnnotationConfiguration
{
    /**
     * @var boolean
     */
    public $withContainer = false;

    /**
     * @var boolean
     */
    public $withFixtures = false;

    /**
     * @var array
     */
    public $envOverloads = [];

    /**
     * @var array
     */
    public $parameterBagOverloads = [];
}
