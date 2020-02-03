<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestTrait;

use RichCongress\Bundle\UnitBundle\OverrideService\OverrideServiceInterface;
use RichCongress\Bundle\UnitBundle\TestCase\TestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithFixtures;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture\LoadDummyEntityData;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;
use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class AuthenticationTraitTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestTrait
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 *
 * @WithFixtures
 * @covers \RichCongress\Bundle\UnitBundle\TestTrait\AuthenticationTrait
 */
class AuthenticationTraitTest extends TestCase
{
    /**
     * @var array
     */
    protected static $userRolesBackup = [];

    /**
     * @var ContainerInterface
     */
    protected static $containerBackup;

    /**
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$userRolesBackup = self::$userRoles;
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        self::$userRoles = self::$userRolesBackup;
        self::$containerBackup = self::$container;
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        self::$container = self::$containerBackup;
    }

    /**
     * @return void
     */
    public function testAuthenticationWithoutUser(): void
    {
        $this->authenticate('');

        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $this->getContainer()->get('security.token_storage');

        self::assertNull($tokenStorage->getToken());
    }

    /**
     * @return void
     */
    public function testAuthenticationWithoutClient(): void
    {
        /** @var User $user */
        $user = $this->getReference('user_2');
        $this->authenticate($user);

        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $this->getContainer()->get('security.token_storage');
        /** @var TokenInterface $token */
        $token = $tokenStorage->getToken();

        self::assertInstanceOf(User::class, $token->getUser());
    }

    /**
     * @return void
     */
    public function testAuthenticationWithoutClientByString(): void
    {
        $this->authenticate('user_2');

        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $this->getContainer()->get('security.token_storage');
        /** @var TokenInterface $token */
        $token = $tokenStorage->getToken();

        self::assertInstanceOf(User::class, $token->getUser());
    }

    /**
     * @return void
     */
    public function testAuthenticationWithClient(): void
    {
        $client = $this->createClientWith('user_1');

        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $client->getContainer()->get('security.token_storage');
        /** @var TokenInterface $token */
        $token = $tokenStorage->getToken();

        self::assertInstanceOf(User::class, $token->getUser());
    }

    /**
     * @return void
     */
    public function testAuthenticationWithoutSecurity(): void
    {
        /** @var User $user */
        $user = $this->getReference('user_2');

        $containerMock = \Mockery::mock(ContainerInterface::class);
        $containerMock->shouldReceive('has')
            ->once()
            ->with('security.token_storage')
            ->andReturnFalse();

        $containerMock->shouldReceive('has')
            ->with(OverrideServicesUtility::class)
            ->andReturnFalse();

        self::$container = $containerMock;

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Fail to authenticate: "security.token_storage" is missing from the container.');

        $this->authenticate($user);
    }

    /**
     * @return void
     */
    public function testRolesDataProviderNoUserRoles(): void
    {
        self::$userRoles = [];

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You must define test_roles in the bundle configuration to use this function.');

        $this->rolesProvider([
            'User1'     => User::class,
        ]);
    }

    /**
     * @return void
     */
    public function testRolesDataProviderBadCount(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The dataProvider has 1 roles where the configuration waits for 2 roles.');

        $this->rolesProvider([
            'User1'     => User::class,
        ]);
    }

    /**
     * @return void
     */
    public function testRolesDataProviderBadKey(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The roles in the given expectations don\'t match the existing roles.');

        $this->rolesProvider([
            'User1'  => User::class,
            'Rudolf' => User::class,
        ]);
    }

    /**
     * Bash testing for multiple users
     *
     * @return array
     */
    public function rolesDataProviderWithArray(): array
    {
        return $this->rolesProvider([
            'User1' => User::class,
            'User2' => [User::class, 'User2'],
        ], [
            'DummyEntity' => ['entity_1', DummyEntity::class]
        ]);
    }

    /**
     * Bash testing for multiple users
     *
     * @return array
     */
    public function rolesDataProvider(): array
    {
        return $this->rolesProvider([
            'User1' => User::class,
            'User2' => User::class,
        ], [
            'DummyEntity' => ['entity_1', DummyEntity::class]
        ]);
    }

    /**
     * @return void
     */
    public function testDataProvider(): void
    {
        $dataProvider = $this->rolesProvider([
            'User1' => User::class,
            'User2' => User::class,
        ], [
            'DummyEntity' => ['entity_1', DummyEntity::class]
        ]);

        self::assertArrayHasKey('User1', $dataProvider);
        self::assertArrayHasKey('User2', $dataProvider);
        self::assertArrayHasKey('DummyEntity', $dataProvider);

        $data = $dataProvider['User1'];
        self::assertContains('user_1', $data);
        self::assertContains(User::class, $data);
    }

    /**
     * @return void
     */
    public function testDataProviderWithArray(): void
    {
        $dataProvider = $this->rolesProvider([
            'User1' => User::class,
            'User2' => [User::class, 'User2'],
        ], [
            'DummyEntity' => ['entity_1', DummyEntity::class]
        ]);

        self::assertArrayHasKey('User1', $dataProvider);
        self::assertArrayHasKey('User2', $dataProvider);
        self::assertArrayHasKey('DummyEntity', $dataProvider);

        $data = $dataProvider['User1'];
        self::assertContains('user_1', $data);
        self::assertContains(User::class, $data);
    }

    /**
     * @dataProvider rolesDataProvider
     *
     * @param string      $reference
     * @param string|null $class
     *
     * @return void
     */
    public function testDataProviderInTest(string $reference, ?string $class): void
    {
        $object = $this->getReference($reference);

        self::assertInstanceOf($class, $object);
    }

    /**
     * @dataProvider rolesDataProviderWithArray
     *
     * @param string      $reference
     * @param string|null $class
     *
     * @return void
     */
    public function testDataProviderInTestWithArray(string $reference, ?string $class): void
    {
        $object = $this->getReference($reference);

        self::assertInstanceOf($class, $object);
    }
}
