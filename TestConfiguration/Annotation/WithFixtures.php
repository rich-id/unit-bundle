<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation;

use RichCongress\Bundle\UnitBundle\TestConfiguration\TestConfiguration;

/**
 * Class WithFixtures
 *
 * @package   RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @Annotation
 */
class WithFixtures implements TestAnnotationInterface
{
    /**
     * @return TestConfiguration
     */
    public function getTestConfiguration(): TestConfiguration
    {
        $config = new TestConfiguration();
        $config->withContainer = true;
        $config->withFixtures = true;

        return $config;
    }
}
