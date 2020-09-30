<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase\Internal;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use RichCongress\Bundle\UnitBundle\Exception\ContainerNotEnabledException;
use RichCongress\Bundle\UnitBundle\Exception\EntityManagerNotFoundException;
use RichCongress\Bundle\UnitBundle\TestConfiguration\TestConfigurationExtractor;
use RichCongress\Bundle\UnitBundle\TestConfiguration\TestContext;
use RichCongress\Bundle\UnitBundle\TestTrait\CommonTestCaseTrait;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WebTestCase
 * Manage the container attribution from the client or the WebTestCase initial container
 *
 * @package   RichCongress\Bundle\UnitBundle\TestCase\Internal
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class WebTestCase extends BaseWebTestCase
{
    /**
     * Allows to mock stuff
     */
    use MockeryPHPUnitIntegration;

    /**
     * Common to every test case
     */
    use CommonTestCaseTrait;

    /**
     * @var KernelBrowser
     */
    private static $client;

    /**
     * WebTestCase constructor.
     *
     * @param string|null $name
     * @param array       $data
     * @param string      $dataName
     */
    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        TestConfigurationExtractor::register(static::class);

        if (self::$container === null && TestConfigurationExtractor::doesClassNeedsContainer(static::class)) {
            self::$container = parent::getContainer();
        }
    }

    /**
     * @internal Use beforeTest instead
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if (TestContext::$needContainer) {
            self::createClient();
        }

        $this->executeBeforeTest();
    }

    /**
     * @internal Use afterTest instead
     *
     * @return void
     */
    public function tearDown(): void
    {
        $this->executeAfterTest();
        self::$client = null;

        parent::tearDown();
    }

    /**
     * Creates a client.
     *
     * @param array $options
     * @param array $server
     *
     * @return KernelBrowser
     */
    public static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        if (self::$client instanceof AbstractBrowser) {
            return self::$client;
        }

        self::$client = parent::createClient($options, $server);

        return self::$client;
    }

    /**
     * @return AbstractBrowser
     */
    protected static function getClient(): AbstractBrowser
    {
        return self::$client;
    }

    /**
     * Gives the client's container if exists, else the WebTestCase container.
     *
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        ContainerNotEnabledException::checkAndThrow();

        return static::$container;
    }

    /**
     * @internal Used by the component that requires an early container
     *
     * @return ContainerInterface
     */
    protected function getParentContainer(): ContainerInterface
    {
        return parent::getContainer();
    }

    /**
     * Gets the entity manager
     *
     * @return EntityManagerInterface
     */
    public function getManager(): EntityManagerInterface
    {
        $container = $this->getContainer();

        try {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $container->get(EntityManagerInterface::class);

            return $entityManager;
        } catch (\Exception $e) {
            /** @var ManagerRegistry $doctrine */
            $doctrine = $container->get('doctrine');
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $doctrine->getManager();
        }

        EntityManagerNotFoundException::checkAndThrow($entityManager);

        return $entityManager;
    }

    /**
     * @param string $service
     *
     * @return object|null
     */
    public function getService(string $service)
    {
        return $this->getContainer()->get($service);
    }

    /**
     * @param string $entityClass
     *
     * @return ObjectRepository|null
     */
    public function getRepository(string $entityClass): ?ObjectRepository
    {
        return $this->getManager()->getRepository($entityClass);
    }

    /**
     * @param string $name
     * @param array  $params
     *
     * @return string
     */
    protected function executeCommand(string $name, array $params = []): string
    {
        ContainerNotEnabledException::checkAndThrow();

        return $this->runCommand($name, $params, true)->getDisplay();
    }
}
