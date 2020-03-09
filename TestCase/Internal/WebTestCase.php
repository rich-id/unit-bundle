<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase\Internal;

use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use RichCongress\Bundle\UnitBundle\TestTrait\CommonTestCaseTrait;
use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;
use RichCongress\Bundle\UnitBundle\Utility\TestConfigurationExtractor;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
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
     * @var boolean
     */
    private static $containerGetBeforeClient = false;

    /**
     * @var boolean
     */
    protected static $isTestInititialized = false;

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

        if (self::$container === null && self::doesClassNeedsContainer()) {
            self::$container = parent::getContainer();
        }
    }

    /**
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        if (self::doesClassNeedsContainer()) {
            parent::setUpBeforeClass();
        }
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        if (self::doesClassNeedsContainer()) {
            parent::tearDownAfterClass();
        }
    }

    /**
     * @internal Use beforeTest instead
     *
     * @return void
     */
    public function setUp(): void
    {
        if ($this->doesTestNeedsContainer()) {
            parent::setUp();

            if (self::$container === null) {
                self::$container = parent::getContainer();
            }
        }

        self::$isTestInititialized = true;
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

        if ($this->doesTestNeedsContainer()) {
            self::$client = null;
            self::$containerGetBeforeClient = false;
            self::$isTestInititialized = false;

            parent::tearDown();
        }
    }

    /**
     * Creates a client
     *
     * @param array $options
     * @param array $server
     *
     * @return KernelBrowser
     */
    public static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        if (self::$containerGetBeforeClient) {
            throw new \RuntimeException('You must create client before any service manipulation.');
        }

        self::$client = parent::createClient($options, $server);

        return self::$client;
    }

    /**
     * Gives the client's container if exists, else the WebTestCase container.
     *
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        if (self::$isTestInititialized) {
            $this->checkContainerEnabled();
        }

        if (self::$client !== null) {
            return self::$client->getContainer();
        }

        if (self::$isTestInititialized) {
            self::$containerGetBeforeClient = true;
        }

        if (self::$container !== null) {
            return self::$container;
        }

        return parent::getContainer();
    }

    /**
     * Gets the entity manager
     *
     * @return EntityManagerInterface|null
     */
    public function getManager(): ?EntityManagerInterface
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
     * @param string $name
     * @param array  $params
     *
     * @return string
     */
    protected function executeCommand(string $name, array $params = []): string
    {
        $this->checkContainerEnabled();

        $commandTester = $this->runCommand($name, $params, true);

        return $commandTester->getDisplay();
    }

    /**
     * @return boolean
     */
    protected static function doesClassNeedsContainer(): bool
    {
        return TestConfigurationExtractor::doesClassNeedsContainer(static::class);
    }

    /**
     * @return boolean
     */
    protected function doesTestNeedsContainer(): bool
    {
        return TestConfigurationExtractor::doesTestNeedsContainer(static::class, $this->getName(false));
    }

    /**
     * @return void
     */
    protected function checkContainerEnabled(): void
    {
        if (!$this->doesTestNeedsContainer()) {
            throw new \LogicException('You did not mentionned that you want to load a container. Add the annotation @WithContainer into the class or test PHP Doc.');
        }
    }
}
