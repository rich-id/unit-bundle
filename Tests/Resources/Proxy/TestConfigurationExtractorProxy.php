<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\Proxy;

use Doctrine\Common\Annotations\AnnotationReader;
use RichCongress\Bundle\UnitBundle\TestConfiguration\TestConfigurationExtractor;

/**
 * Class TestConfigurationExtractorProxy
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\Proxy
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class TestConfigurationExtractorProxy extends TestConfigurationExtractor
{
    /**
     * @var AnnotationReader
     */
    public static $annotationReader;
}
