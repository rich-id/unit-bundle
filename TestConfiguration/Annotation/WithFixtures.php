<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation;

use RichCongress\Bundle\UnitBundle\TestConfiguration\AnnotationConfiguration;

/**
 * Class WithFixtures
 *
 * @package   RichCongress\Bundle\UnitBundle\AnnotationConfiguration\Annotation
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @Annotation
 */
class WithFixtures implements TestAnnotationInterface
{
    /**
     * @return AnnotationConfiguration
     */
    public function getTestConfiguration(): AnnotationConfiguration
    {
        $config = new AnnotationConfiguration();
        $config->withContainer = true;
        $config->withFixtures = true;

        return $config;
    }
}
