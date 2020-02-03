<?php
/** @noinspection ALL */
declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestTrait;

use Mockery\Container;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
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
     * @param UserInterface|string $user
     * @param Client|null   $client
     *
     * @return void
     */
    public function authenticate($user, Client $client = null): void
    {
        $this->checkFixturesEnabled();

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

        $tokenStorage = $container->get('security.token_storage');

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
     * @return Client
     */
    public function createClientWith($user = null): KernelBrowser
    {
        $this->checkFixturesEnabled();

        $client = self::createClient();

        if (is_string($user) && $user !== '') {
            /** @var UserInterface $user */
            $user = $this->getReference($user);
        }

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
        $this->checkFixturesEnabled();

        $rolesCount = count(self::$userRoles);

        if ($rolesCount === 0) {
            throw new \LogicException('You must define test_roles in the bundle configuration to use this function.');
        }

        $countExpectations = count($expectations);
        $rolesNames = array_keys(self::$userRoles);
        $rolesReferences = array_values(self::$userRoles);

        if ($countExpectations !== $rolesCount) {
            throw new \LogicException(
                sprintf(
                    'The dataProvider has %d roles where the configuration waits for %d roles.',
                    $countExpectations,
                    $rolesCount
                )
            );
        }

        if ($rolesNames !== array_keys($expectations)) {
            throw new \LogicException('The roles in the given expectations don\'t match the existing roles.');
        }

        $mapUserExpectation = array_map(
            static function ($userReference, $expectation) {
                if (is_array($expectation)) {
                    array_unshift($expectation, $userReference);

                    return $expectation;
                }

                return [$userReference, $expectation];
            },
            array_values(self::$userRoles),
            array_values($expectations)
        );

        return array_merge(array_combine($rolesNames, $mapUserExpectation), $extraRoles);
    }

    /**
     * @internal
     *
     * @return void
     */
    protected function authenticationTearDown(): void
    {
        $container = $this->getContainer();

        if ($container->has('security.token_storage')) {
            /** @var TokenStorageInterface $tokenStorage */
            $tokenStorage = $container->get('security.token_storage');
            $tokenStorage->setToken(null);
        }
    }
}
