<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\OverrideService;

/**
 * Interface OverrideServiceInterface
 *
 * @package   RichCongress\Bundle\UnitBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 *
 * To implement `setUp` and `tearDown` functions, you may respectively use `__construct` and `__destruct` as each
 *  service is built if autowired and destroyed for each test
 */
interface OverrideServiceInterface
{
    /**
     * @return array
     */
    public static function getOverridenServiceNames(): array;
}
