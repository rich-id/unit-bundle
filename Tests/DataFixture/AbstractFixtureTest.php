<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\DataFixture;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture\LoadDummyEntityData;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture\LoadUserData;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\DataFixture\AbstractFixture;

/**
 * Class AbstractFixtureTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\DataFixture
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\DataFixture\AbstractFixture
 * @covers \RichCongress\Bundle\UnitBundle\TestTrait\FixtureCreationTrait
 */
class AbstractFixtureTest extends MockeryTestCase
{
    /**
     * @return void
     */
    public function testCreateObject(): void
    {
        $manager = \Mockery::mock(ObjectManager::class);
        $manager->shouldReceive('flush')->once();
        $manager->shouldReceive('persist')
            ->times(22)
            ->with(\Mockery::type(DummyEntity::class));

        $repository = \Mockery::mock(ReferenceRepository::class);
        $repository->shouldReceive('setReference')
            ->times(22)
            ->with(
                \Mockery::type('string'),
                \Mockery::type(DummyEntity::class)
            );

        $repository->shouldReceive('getReference')
            ->once()
            ->with('entity_1')
            ->andReturn(new DummyEntity());

        /** @var AbstractFixture|MockInterface $abstractFixture */
        $abstractFixture = new LoadDummyEntityData();
        $abstractFixture->setReferenceRepository($repository);
        $abstractFixture->load($manager);
    }

    /**
     * @return void
     */
    public function testBuildObject(): void
    {
        $data = [
            'name' => 'Name',
            'keyname' => 'Keyname',
        ];

        /** @var DummyEntity $object */
        $object = AbstractFixture::buildObject(DummyEntity::class, $data);

        self::assertInstanceOf(DummyEntity::class, $object);
        self::assertSame('Name', $object->getName());
        self::assertSame('Keyname', $object->getKeyname());
    }

    /**
     * @return void
     */
    public function testSetValue(): void
    {
        $object = new DummyEntity();
        AbstractFixture::setValue($object, 'name', 'Name');

        self::assertSame('Name', $object->getName());
    }

    /**
     * @return void
     */
    public function testGetReferenceInDoctrine(): void
    {
        $repository = \Mockery::mock(ReferenceRepository::class);
        $object = new DummyEntity();
        $fixture = new LoadDummyEntityData();
        $fixture->setReferenceRepository($repository);

        $repository->shouldReceive('getReference')
            ->once()
            ->with('entity_1')
            ->andReturn($object);

        $result = $fixture->getReference('entity_1');

        self::assertSame($object, $result);
    }

    /**
     * @return void
     */
    public function testCreateFromDefaultFailure(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You must override "generateDefaultEntity" to generate the default entity.');

        $abstractFixture = new LoadUserData();
        $abstractFixture->badLoadFixtures();
    }

    /**
     * @return void
     */
    public function testSetValueParentProperty(): void
    {
        /** @var DummyEntity $result */
        $result = AbstractFixture::buildObject(DummyEntity::class, [
            'parentProperty' => true,
        ]);

        self::assertTrue($result->parentProperty);
    }

    /**
     * @return void
     */
    public function testSetValueOnNonExistingProperty(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The property "nonExistingProperty" does not exist for the entity ' . DummyEntity::class);

        AbstractFixture::buildObject(DummyEntity::class, [
            'nonExistingProperty' => true,
        ]);
    }
}
