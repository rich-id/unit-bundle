<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Utility;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Mockery\MockInterface;
use RichCongress\Bundle\UnitBundle\Stubs\ContainerStub;
use RichCongress\Bundle\UnitBundle\TestCase\TestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithFixtures;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture\LoadDummyEntityData;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture\LoadUserData;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DummyDatabaseTool;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Proxy\FixturesManagerProxy;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Stub\ReferenceRepositoryStub;

/**
 * Class FixturesManagerTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Utility
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Utility\FixturesManager
 */
class FixturesManagerTest extends TestCase
{
    /**
     * @var DatabaseToolCollection|MockInterface
     */
    protected $databaseTool;

    /**
     * @var FixturesManagerProxy
     */
    protected $fixturesManager;

    /**
     * @return void
     *
     * @throws AnnotationException
     */
    public function beforeTest(): void
    {
        $this->databaseTool = new DummyDatabaseTool();

        $doctrine = \Mockery::mock(ManagerRegistry::class);
        $doctrine->shouldReceive('getName')->andReturn($this->databaseTool->getType());

        $container = new ContainerStub();
        $container->set('doctrine', $doctrine);

        $databaseToolCollection = new DatabaseToolCollection($container, new AnnotationReader());
        $databaseToolCollection->add($this->databaseTool);

        $this->fixturesManager = new FixturesManagerProxy($databaseToolCollection);
    }

    /**
     * @return void
     */
    public function testSetFixturesClasses(): void
    {
        FixturesManagerProxy::setFixturesClasses([
            LoadDummyEntityData::class => '',
            LoadUserData::class => '',
        ]);

        self::assertCount(2, FixturesManagerProxy::$fixturesClasses);
        self::assertContains(LoadDummyEntityData::class, FixturesManagerProxy::$fixturesClasses);
        self::assertContains(LoadUserData::class, FixturesManagerProxy::$fixturesClasses);
    }

    /**
     * @return void
     *
     * @throws \Throwable
     */
    public function testLoadFixturesNoFixtures(): void
    {
        $this->expectOutputRegex(
            $this->buildRegex(['Database initialization', 'Database initialized!'])
        );

        FixturesManagerProxy::setFixturesClasses([]);
        FixturesManagerProxy::loadFixtures();

        self::assertNotNull(FixturesManagerProxy::$fixtures);
        self::assertEmpty(FixturesManagerProxy::$fixtures->getIdentities());
    }

    /**
     * @WithFixtures()
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function testLoadFixtures(): void
    {
        FixturesManagerProxy::setFixturesClasses([
            LoadDummyEntityData::class => '',
            LoadUserData::class => '',
        ]);

        $this->expectOutputRegex(
            $this->buildRegex(['Database initialization', 'Database initialized!'])
        );

        FixturesManagerProxy::loadFixtures();

        self::assertInstanceOf(ReferenceRepository::class, FixturesManagerProxy::$fixtures);
        self::assertInstanceOf(User::class, FixturesManagerProxy::getReference('user'));
        self::assertInstanceOf(DummyEntity::class, FixturesManagerProxy::getReference('dummy_entity'));
    }

    /**
     * @WithFixtures()
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function testLoadFixturesWithError(): void
    {
        $this->expectOutputRegex(
            $this->buildRegex([
                'Database initialization',
                'The following error occured during the fixtures loading:'
            ])
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Fake error');

        FixturesManagerProxy::setFixturesClasses(['Error' => '']);
        FixturesManagerProxy::loadFixtures();
    }

    /**
     * @return void
     */
    public function testSetReference(): void
    {
        $object = new DummyEntity();
        FixturesManagerProxy::$fixtures = new ReferenceRepositoryStub();
        FixturesManagerProxy::setReference('new_reference', $object);

        self::assertSame($object, FixturesManagerProxy::getReference('new_reference'));
    }

    /**
     * @return void
     */
    public function testGetReferences(): void
    {
        $object = new DummyEntity();
        FixturesManagerProxy::$fixtures = new ReferenceRepositoryStub();
        FixturesManagerProxy::setReference('new_reference', $object);

        self::assertArrayHasKey('new_reference', FixturesManagerProxy::getReferences());
    }

    /**
     * @return void
     */
    public function testGetReferenceNotFound(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('The reference "unknown_reference" does not exist. Did you mean "new_reference"?');

        $object = new DummyEntity();
        FixturesManagerProxy::$fixtures = new ReferenceRepositoryStub();
        FixturesManagerProxy::setReference('new_reference', $object);

        FixturesManagerProxy::getReference('unknown_reference');
    }

    /**
     * @return void
     */
    public function testGetIdentity(): void
    {
        $object = new DummyEntity();
        FixturesManagerProxy::$fixtures = new ReferenceRepositoryStub();
        FixturesManagerProxy::setReference('new_reference', $object);

        self::assertSame('identity_new_reference', FixturesManagerProxy::getIdentity('new_reference'));
        self::assertNull(FixturesManagerProxy::getIdentity('unknown_reference'));
    }

    /**
     * @param array $expectedItems
     *
     * @return string
     */
    protected function buildRegex(array $expectedItems): string
    {
        $regex = '\X*';

        foreach ($expectedItems as $expectedItem) {
            $regex .= sprintf('(%s)\X*', $expectedItem);
        }

        return '/' . $regex . '/';
    }
}
