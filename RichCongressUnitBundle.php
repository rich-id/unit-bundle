<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle;

use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\DataFixturesPass;
use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\OverrideServicesPass;
use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\PublicServicesPass;
use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;
use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;
use RichCongress\BundleToolbox\Configuration\AbstractBundle;

/**
 * Class RichCongressUnitBundle
 *
 * @package   RichCongress\Bundle\UnitBundle
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class RichCongressUnitBundle extends AbstractBundle
{
    public const COMPILER_PASSES = [
        DataFixturesPass::class,
        OverrideServicesPass::class,
        PublicServicesPass::class,
    ];

    /**
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        // Autowire everything for the FixturesManager before the first test
        $this->container->get(OverrideServicesUtility::class);
        $this->container->get(FixturesManager::class);
    }
}
