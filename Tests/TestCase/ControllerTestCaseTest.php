<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestCase;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Doctrine\ORM\EntityManagerInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class ControllerTestCaseTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ControllerTestCaseTest extends MockeryTestCase
{
    /**
     * @covers \RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase::getCsrfToken()
     *
     * @return void
     */
    public function testGetCsrfTokenWithContainerHasIntention(): void
    {
        $container = \Mockery::mock(ContainerInterface::class);
        $client = \Mockery::mock(Client::class);
        $tokenManager = \Mockery::mock(CsrfTokenManagerInterface::class);

        $client->shouldReceive('getContainer')
            ->once()
            ->andReturn($container);

        $container->shouldReceive('has')
            ->once()
            ->with('intention')
            ->andReturnTrue();

        $container->shouldReceive('get')
            ->once()
            ->with('intention')
            ->andReturn(new FormType());

        $container->shouldReceive('get')
            ->once()
            ->with('security.csrf.token_manager')
            ->andReturn($tokenManager);

        $tokenManager->shouldReceive('getToken')
            ->once()
            ->with('form')
            ->andReturn('csrfToken');

        $token = ControllerTestCase::getCsrfToken('intention', $client);

        self::assertSame('csrfToken', $token);
    }

    /**
     * @covers \RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase::getCsrfToken()
     *
     * @return void
     */
    public function testGetCsrfTokenWithContainerHasNotIntentionButSubclass(): void
    {
        $container = \Mockery::mock(ContainerInterface::class);
        $client = \Mockery::mock(Client::class);
        $tokenManager = \Mockery::mock(CsrfTokenManagerInterface::class);

        $client->shouldReceive('getContainer')
            ->once()
            ->andReturn($container);

        $container->shouldReceive('has')
            ->once()
            ->with(FormType::class)
            ->andReturnFalse();

        $container->shouldReceive('get')
            ->once()
            ->with('security.csrf.token_manager')
            ->andReturn($tokenManager);

        $tokenManager->shouldReceive('getToken')
            ->once()
            ->with('form')
            ->andReturn('csrfToken');

        $token = ControllerTestCase::getCsrfToken(FormType::class, $client);

        self::assertSame('csrfToken', $token);
    }

    /**
     * @covers \RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase::getCsrfToken()
     *
     * @return void
     */
    public function testGetCsrfTokenWithContainerHasNotIntentionAndNotSubclass(): void
    {
        $container = \Mockery::mock(ContainerInterface::class);
        $client = \Mockery::mock(Client::class);
        $tokenManager = \Mockery::mock(CsrfTokenManagerInterface::class);

        $client->shouldReceive('getContainer')
            ->once()
            ->andReturn($container);

        $container->shouldReceive('has')
            ->once()
            ->with('intention')
            ->andReturnFalse();

        $container->shouldReceive('get')
            ->once()
            ->with('security.csrf.token_manager')
            ->andReturn($tokenManager);

        $tokenManager->shouldReceive('getToken')
            ->once()
            ->with('intention')
            ->andReturn('csrfToken');

        $token = ControllerTestCase::getCsrfToken('intention', $client);

        self::assertSame('csrfToken', $token);
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

        $client = \Mockery::mock(Client::class);
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
