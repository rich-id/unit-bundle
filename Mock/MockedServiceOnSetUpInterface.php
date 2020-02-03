<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Mock;

/**
 * Interface MockedServiceOnSetUpInterface
 *
 * @package   RichCongress\Bundle\UnitBundle\Mock
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
interface MockedServiceOnSetUpInterface
{
    /**
     * Return an associative array
     *
     * example:
     * ```
     *      $knpPdf = \Mockery::mock('Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator');
     *      $knpPdf->shouldReceive('getOutputFromHtml')->andReturn('example');
     *      $knpPdf->shouldReceive('getOutput')->andReturn('example');
     *
     *      return ['knp_snappy.pdf' => $knpPdf]
     * ```
     *
     * @return array
     */
    public static function getMockedServices(): array;
}
