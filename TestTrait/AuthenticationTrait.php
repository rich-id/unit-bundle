<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestTrait;

use RichCongress\Bundle\UnitBundle\Exception\BadTestRolesException;
use RichCongress\Bundle\UnitBundle\Exception\FixturesNotEnabledException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Trait AuthenticationTrait
 *
 * @package   RichCongress\Bundle\UnitBundle\TestTrait
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
trait AuthenticationTrait
{
    /**
     * User roles mapped to appropriate user
     *
     * @var array
     */
    protected static $userRoles;

    /**
     * @return array
     */
    public function getUserRoles(): array
    {
        /** @var ContainerInterface $container */
        $container = $this->getContainer();

        if (self::$userRoles === null) {
            self::$userRoles = $container->hasParameter('rich_congress_unit.test_roles')
                ? $container->getParameter('rich_congress_unit.test_roles')
                : [];
        }

        return self::$userRoles;
    }

    /**
     * @param UserInterface|string $user
     * @param KernelBrowser        $client
     *
     * @return void
     */
    public function authenticate($user, KernelBrowser $client = null): void
    {
        FixturesNotEnabledException::checkAndThrow();

        if (!$user instanceof UserInterface && (!\is_string($user) || $user === '')) {
            return;
        }

        /** @var ContainerInterface $container */
        $container = self::$container;

        if (!$container->has('security.token_storage')) {
            throw new \LogicException('Fail to authenticate: "security.token_storage" is missing from the container.');
        }

        if (\is_string($user)) {
            $user = $this->getReference($user);
        }

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $container->get('security.token_storage');
        $tokenStorage->setToken($token);

        if ($client === null) {
            return;
        }

        /** @var ContainerInterface $container */
        $container = $client->getContainer();
        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $container->get('security.token_storage');
        $tokenStorage->setToken($token);

        /** @var SessionInterface $session */
        $session = $container->get('session');
        $session->set('_security_main', \serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    /**
     * @param UserInterface|string|null $userReference
     *
     * @return KernelBrowser
     */
    public function createClientWith($userReference = null): KernelBrowser
    {
        FixturesNotEnabledException::checkAndThrow();
        $client = self::createClient();

        $user = (is_string($userReference) && $userReference !== '')
            ? $this->getReference($userReference)
            : $userReference;

        if ($user instanceof UserInterface) {
            $this->authenticate($user, $client);
        }

        return $client;
    }

    /**
     * @param array $expectations
     * @param array $extraRoles
     *
     * @return array
     */
    public function rolesProvider(array $expectations, array $extraRoles = []): array
    {
        $userRoles = $this->getUserRoles();
        $rolesNames = array_keys($userRoles);

        $mapUserExpectation = array_map(
            static function ($userReference, $expectation) {
                if (is_array($expectation)) {
                    array_unshift($expectation, $userReference);

                    return $expectation;
                }

                return [$userReference, $expectation];
            },
            array_values($userRoles),
            array_values($expectations)
        );

        BadTestRolesException::checkAndThrow($userRoles, $expectations);

        return array_merge(array_combine($rolesNames, $mapUserExpectation), $extraRoles);
    }

    /**
     * @internal
     *
     * @return void
     */
    protected function authenticationTearDown(): void
    {
        /** @var ContainerInterface $container */
        $container = $this->getContainer();

        if ($container->has('security.token_storage')) {
            /** @var TokenStorageInterface $tokenStorage */
            $tokenStorage = $container->get('security.token_storage');
            $tokenStorage->setToken(null);
        }
    }
}
