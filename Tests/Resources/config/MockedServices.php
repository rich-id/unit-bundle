<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\config;

use GuzzleHttp\Client;
use RichCongress\Bundle\UnitBundle\Mock\MockedServiceOnSetUpInterface;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;

/**
 * Class MockedServices
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\config
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class MockedServices implements MockedServiceOnSetUpInterface
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
    public static function getMockedServices(): array
    {
        return [
            'dummy_entity' => \Mockery::mock(DummyEntity::class),
            'eight_points_guzzle.client.dummy_api' => \Mockery::mock(Client::class),
        ];
    }
}
