<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Utility;

use RichCongress\Bundle\UnitBundle\OverrideService\OverrideServiceInterface;
use RichCongress\Bundle\UnitBundle\TestCase\Internal\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class OverrideServicesUtility
 *
 * @package   RichCongress\Bundle\UnitBundle\Utility
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class OverrideServicesUtility
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array|OverrideServiceInterface[]
     */
    protected $newServices = [];

    /**
     * $serviceId => $priority
     *
     * @var array|int[]
     */
    protected $priorities = [];

    /**
     * OverrideServicesUtility constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param OverrideServiceInterface $overrideService
     * @param integer                  $priority
     *
     * @return void
     */
    public function addOverrideService(OverrideServiceInterface $overrideService, int $priority = 0): void
    {
        $serviceId = $overrideService->getOverridenServiceName();

        // The service already registered has an higher priority
        if ($serviceId === null || (array_key_exists($serviceId, $this->priorities) && $this->priorities[$serviceId] >= $priority)) {
            return;
        }

        $this->priorities[$serviceId] = $priority;
        $this->newServices[$serviceId] = $overrideService;
    }

    /**
     * @return array|string[]
     */
    public function getOverridenServiceIds(): array
    {
        return array_keys($this->newServices);
    }

    /**
     * @return void
     *
     * @throws \ReflectionException
     */
    public function overrideServices(): void
    {
        /** @var OverrideServiceInterface $overrideService */
        foreach ($this->newServices as $serviceId => $overrideService) {
            WebTestCase::overrideService($this->container, $serviceId, $overrideService);
        }
    }

    /**
     * @return void
     */
    public function executeSetUps(): void
    {
        foreach ($this->newServices as $overrideService) {
            $overrideService->setUp();
        }
    }

    /**
     * @return void
     */
    public function executeTearDowns(): void
    {
        foreach ($this->newServices as $overrideService) {
            $overrideService->tearDown();
        }
    }
}
