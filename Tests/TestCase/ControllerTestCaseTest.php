<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestCase;

use RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithContainer;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Form\DummyFormType;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ControllerTestCaseTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @WithContainer
 */
class ControllerTestCaseTest extends ControllerTestCase
{
    /**
     * @covers \RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase::getCsrfToken()
     *
     * @return void
     */
    public function testGetCsrfTokenWithContainerHasIntention(): void
    {
        $token = $this->getCsrfToken('dummy_form');

        self::assertNotNull($token);
    }
    /**
     * @covers \RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase::getCsrfToken()
     *
     * @return void
     */
    public function testGetCsrfTokenWithContainerHasIntentioan(): void
    {
        $token = $this->getCsrfToken('dummy_form_type');

        self::assertNotNull($token);
    }

    /**
     * @covers \RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase::getCsrfToken()
     *
     * @return void
     */
    public function testGetCsrfTokenWithContainerHasNotIntentionButSubclass(): void
    {
        $token = $this->getCsrfToken(DummyFormType::class);

        self::assertNotNull($token);
    }

    /**
     * @covers \RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase::parseQueryParams()
     *
     * @return void
     */
    public function testParseQueryParams(): void
    {
        $data = [
            'param1' => 'value1',
            'param2' => 'value2',
            'param3' => 3,
            'array'  => [
                'arrayValue1',
                'arrayValue2',
            ]
        ];

        $params = ControllerTestCase::parseQueryParams($data);

        self::assertSame(
            '?param1=value1&param2=value2&param3=3&array[0]=arrayValue1&array[1]=arrayValue2',
            \urldecode($params)
        );
    }

    /**
     * @covers \RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase::getJsonContent()
     *
     * @return void
     */
    public function testGetJsonContent(): void
    {
        $response = \Mockery::mock(Response::class);
        $response->shouldReceive('getContent')
            ->once()
            ->andReturn('[{"test": true}]');

        $client = \Mockery::mock(KernelBrowser::class);
        $client->shouldReceive('getResponse')
            ->once()
            ->andReturn($response);

        $content = ControllerTestCase::getJsonContent($client);

        self::assertSame(
            [['test' => true]],
            $content
        );
    }
}
