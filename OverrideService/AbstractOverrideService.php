<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\OverrideService;

use RichCongress\Bundle\UnitBundle\TestTrait\OverrideServiceTrait;

/**
 * Class AbstractOverrideService
 *
 * @package   RichCongress\Bundle\UnitBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
abstract class AbstractOverrideService implements OverrideServiceInterface
{
    /**
     * /!\ Needs to be overriden
     * Name of the service to override
     */
    public static $overridenServices = [];

    use OverrideServiceTrait;
}
