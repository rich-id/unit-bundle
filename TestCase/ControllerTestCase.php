<?php /** @noinspection NullPointerExceptionInspection */
/** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase;

use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Form\FormTypeInterface;

/**
 * Class ControllerTestCase
 *
 * @package   RichCongress\Bundle\UnitBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class ControllerTestCase extends TestCase
{
    /**
     * Get the CSRF token from the Type and the Client
     *
     * @param string $intention
     * @param Client $client
     *
     * @return string
     */
    public static function getCsrfToken(string $intention, Client $client): string
    {
        /** @var ContainerInterface $container */
        $container = $client->getContainer();
        $class = null;

        if ($container->has($intention)) {
            $class = $container->get($intention);
        }

        elseif (\is_subclass_of($intention, FormTypeInterface::class)) {
            $class = new $intention();
        }

        if ($class !== null) {
            $intention = $class->getBlockPrefix() ?? $intention;
        }

        return (string) $container
            ->get('security.csrf.token_manager')
            ->getToken($intention);
    }

    /**
     * Transform an array of query parameters in a string for URL
     *
     * @param array $queryParams
     *
     * @return string
     */
    public static function parseQueryParams(array $queryParams): string
    {
        return '?' . \http_build_query($queryParams);
    }

    /**
     * Extract Json content from the client
     *
     * @param Client  $client
     * @param boolean $assoc
     *
     * @return array|object|null
     */
    public static function getJsonContent(Client $client, bool $assoc = true)
    {
        return \json_decode($client->getResponse()->getContent(), $assoc, 512, JSON_THROW_ON_ERROR);
    }
}
