<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestCase\Internal;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use RichCongress\Bundle\UnitBundle\Resources\Stub\KernelTestCaseStub;
use RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase;
use RichCongress\Bundle\UnitBundle\TestCase\Internal\FixturesTestCase;
use RichCongress\Bundle\UnitBundle\TestCase\Internal\WebTestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithContainer;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithFixtures;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;
use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class FixtureTestCaseTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestCase\Internal
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestCase\Internal\FixturesTestCase
 */
class FixturesTestCaseTest extends FixturesTestCase
{
    /**
     * @WithFixtures
     *
     * @return void
     */
    public function testConstructor(): void
    {
        FixturesManager::$needFixturesLoading = false;
        self::$userRoles = null;
        self::$isTestInititialized = false;

        new self();

        self::assertTrue(FixturesManager::$needFixturesLoading);
        self::assertNotNull(self::$userRoles);
    }

    /**
     * @WithFixtures
     *
     * @return void
     */
    public function testGetReferenceWithoutCaching(): void
    {
        $client = self::createClient();

        /** @var DummyEntity $dummyEntity */
        $dummyEntity = $this->getReference('entity_2');

        self::assertSame('Name 2', $dummyEntity->getName());
    }

    /**
     * @WithFixtures
     *
     * @return void
     */
    public function testGetReferenceFromClient(): void
    {
        $client = self::createClient();

        /** @var DummyEntity $dummyEntity */
        $dummyEntity = $this->getReference('entity_2');

        self::assertSame('Name 2', $dummyEntity->getName());
    }

    /**
     * @WithFixtures
     *
     * @return void
     */
    public function testTestEnclosureDelete(): void
    {
        /** @var DummyEntity $entity */
        $entity = $this->getReference('entity_1');
        $id = $entity->getId();

        self::assertSame('Name 1', $entity->getName());

        $entityManager = $this->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();

        self::assertNull($entity->getId());
    }

    /**
     * @WithFixtures
     *
     * @return void
     */
    public function testTestEnclosureGet(): void
    {
        /** @var DummyEntity $entity */
        $entity = $this->getReference('entity_1');

        self::assertNotNull($entity);
    }

    /**
     * @WithFixtures
     *
     * @return void
     */
    public function testTestGetReferenceNotExisting(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        /** @var DummyEntity $entity */
        $this->getReference('unknown_entity');
    }

    /**
     * @WithFixtures
     *
     * @return void
     */
    public function testUnknownReference(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        /** @var DummyEntity $entity */
        $this->getReference('unknown_entity');
    }

    /**
     * @WithFixtures
     *
     * @return void
     */
    public function testDoesClassAndTestNeedsFixtures(): void
    {
        self::assertTrue(self::doesClassNeedsFixtures());
        self::assertTrue($this->doesTestNeedsFixtures());
    }

    /**
     * @WithContainer
     *
     * @return void
     */
    public function testDoesClassAndTestNeedsFixturesWithNoFixtures(): void
    {
        self::assertTrue(self::doesClassNeedsFixtures());
        self::assertFalse($this->doesTestNeedsFixtures());
    }

    /**
     * @return void
     */
    public function testCheckFixturesEnabledWithoutFixtures(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You did not mentionned that you want to load the fixtures. Add the annotation @WithFixtures into the class or test PHP Doc.');

        $this->checkFixturesEnabled();
    }
}
