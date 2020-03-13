<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase;

use RichCongress\Bundle\UnitBundle\Exception\CsrfTokenManagerMissingException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

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
     *
     * @return string
     */
    public function getCsrfToken(string $intention): string
    {
        /** @var ContainerInterface $container */
        $container = $this->getContainer();
        $class = null;

        CsrfTokenManagerMissingException::checkAndThrow($container);
        /** @var CsrfTokenManagerInterface $csrfTokenManager */
        $csrfTokenManager = $container->get('security.csrf.token_manager');

        if ($container->has($intention)) {
            $class = $container->get($intention);
        } elseif (\is_subclass_of($intention, FormTypeInterface::class)) {
            $class = new $intention();
        }

        if ($class !== null) {
            $intention = $class->getBlockPrefix() ?? $intention;
        }

        return (string) $csrfTokenManager->getToken($intention);
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
     * @param KernelBrowser $client
     * @param boolean       $assoc
     *
     * @return array|object|null
     */
    public static function getJsonContent(KernelBrowser $client, bool $assoc = true)
    {
        return \json_decode($client->getResponse()->getContent(), $assoc, 512, JSON_THROW_ON_ERROR);
    }
}
