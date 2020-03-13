<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation;

use RichCongress\Bundle\UnitBundle\TestConfiguration\AnnotationConfiguration;

/**
 * Interface TestAnnotationInterface
 *
 * @package   RichCongress\Bundle\UnitBundle\AnnotationConfiguration\Annotation
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
interface TestAnnotationInterface
{
    /**
     * @return AnnotationConfiguration
     */
    public function getTestConfiguration(): AnnotationConfiguration;
}
