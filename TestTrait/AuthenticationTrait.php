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
        if (self::$userRoles === null) {
            /** @var ContainerInterface $container */
            $container = $this->getParentContainer();

            self::$userRoles = $container->hasParameter('rich_congress_unit.test_roles')
                ? $container->getParameter('rich_congress_unit.test_roles')
                : [];
        }

        return self::$userRoles;
    }

    /**
     * @param UserInterface|string $user
     * @param string               $sessionAttribute
     *
     * @return void
     */
    public function authenticate($user, string $sessionAttribute = '_security_main'): void
    {
        FixturesNotEnabledException::checkAndThrow();

        if (!$user instanceof UserInterface && (!\is_string($user) || $user === '')) {
            return;
        }

        /** @var ContainerInterface $container */
        $container = $this->getContainer();
        $tokenStorage = $this->getSecurityTokenStorage();

        if ($tokenStorage === null) {
            throw new \LogicException('Fail to authenticate: "security.token_storage" is missing from the container.');
        }

        if (\is_string($user)) {
            $user = $this->getReference($user);
        }

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $container->get('security.token_storage');
        $tokenStorage->setToken($token);

        /** @var SessionInterface $session */
        $session = $container->get('session');
        $session->set($sessionAttribute, \serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        static::getClient()->getCookieJar()->set($cookie);
    }

    /**
     * @param UserInterface|string|null $userReference
     * @param string                    $securityAttribute
     *
     * @return KernelBrowser
     */
    public function createClientWith($userReference = null, string $securityAttribute = '_security_main'): KernelBrowser
    {
        FixturesNotEnabledException::checkAndThrow();
        $this->authenticate($userReference, $securityAttribute);

        return static::getClient();
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
        $tokenStorage = $this->getSecurityTokenStorage();

        if ($tokenStorage !== null) {
            $tokenStorage->setToken(null);
        }
    }

    /**
     * @return TokenStorageInterface|null
     */
    private function getSecurityTokenStorage(): ?TokenStorageInterface
    {
        $tokenStorage = $this->getContainer()->has('security.token_storage')
            ? $this->getContainer()->get('security.token_storage')
            : null;

        return $tokenStorage instanceof TokenStorageInterface ? $tokenStorage : null;
    }
}
