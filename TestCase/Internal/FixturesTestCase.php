<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase\Internal;

use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use RichCongress\Bundle\UnitBundle\Mock\MockedServiceOnSetUpInterface;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithFixtures;
use RichCongress\Bundle\UnitBundle\TestTrait\AuthenticationTrait;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture\LoadDummyEntityData;
use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;
use RichCongress\Bundle\UnitBundle\Utility\TestConfigurationExtractor;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class FixtureTestCase
 *
 * @package   RichCongress\Bundle\UnitBundle\TestCase\Internal
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class FixturesTestCase extends WebTestCase
{
    /**
     * Import the authentication trait
     */
    use AuthenticationTrait;

    /**
     * FixturesTestCase constructor.
     *
     * @param string|null $name
     * @param array       $data
     * @param string      $dataName
     */
    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        if (self::doesClassNeedsFixtures()) {
            FixturesManager::$needFixturesLoading = true;

            self::$userRoles = $this->getContainer()->hasParameter('rich_congress_unit.test_roles')
                ? $this->getContainer()->getParameter('rich_congress_unit.test_roles')
                : [];
        }
    }

    /**
     * @internal Use afterTest instead
     *
     * @return void
     */
    public function tearDown(): void
    {
        $this->executeAfterTest();

        if ($this->doesTestNeedsFixtures()) {
            $this->authenticationTearDown();
        }

        parent::tearDown();
    }

    /**
     * Loads the reference with the appropriate EntityManager
     *
     * @param string $reference
     *
     * @return mixed
     */
    protected function getReference(string $reference)
    {
        $this->checkFixturesEnabled();

        $object = FixturesManager::getReference($reference);
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getManager();
        $meta = $entityManager->getClassMetadata(\get_class($object));
        $identity = FixturesManager::getIdentity($reference);

        if ($identity !== null && !$entityManager->contains($object)) {
            $object = $entityManager->getReference(
                $meta->getName(),
                $identity
            );

            FixturesManager::setReference($reference, $object);
        }

        return $object;
    }

    /**
     * @return boolean
     */
    protected static function doesClassNeedsFixtures(): bool
    {
        return TestConfigurationExtractor::doesClassNeedsFixtures(static::class);
    }

    /**
     * @return boolean
     */
    protected function doesTestNeedsFixtures(): bool
    {
        return TestConfigurationExtractor::doesTestNeedsFixtures(static::class, $this->getName(false));
    }

    /**
     * @return void
     */
    protected function checkFixturesEnabled(): void
    {
        if (!$this->doesTestNeedsFixtures()) {
            throw new \LogicException('You did not mentionned that you want to load the fixtures. Add the annotation @WithFixtures into the class or test PHP Doc.');
        }
    }
}
