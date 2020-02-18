<?php

namespace RichCongress\Bundle\UnitBundle\Kernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Liip\FunctionalTestBundle\LiipFunctionalTestBundle;
use Liip\TestFixturesBundle\LiipTestFixturesBundle;
use RichCongress\Bundle\UnitBundle\RichCongressUnitBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AbstractTestKernel
 *
 * @package   RichCongress\Bundle\UnitBundle\Kernel
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
abstract class AbstractTestKernel extends Kernel
{
    /**
     * @return array|iterable|BundleInterface[]
     */
    public function registerBundles()
    {
        return [
            new DoctrineBundle(),
            new DoctrineFixturesBundle(),
            new FrameworkBundle(),
            new LiipFunctionalTestBundle(),
            new LiipTestFixturesBundle(),
            new DAMADoctrineTestBundle(),
            new RichCongressUnitBundle(),
        ];
    }
}
