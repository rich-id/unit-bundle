<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler;

use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class OverrideServicesPass
 *
 * @package   RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class OverrideServicesPass implements CompilerPassInterface
{
    public const OVERRIDE_SERVICE_TAG = 'rich_congress.unit_bundle.override_service';

    use PriorityTaggedServiceTrait;

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(OverrideServicesUtility::class)) {
            return;
        }

        $utilityDefinition = $container->findDefinition(OverrideServicesUtility::class);
        $taggedServices = $this->findAndSortTaggedServices(self::OVERRIDE_SERVICE_TAG, $container);

        foreach ($taggedServices as $service) {
            $utilityDefinition->addMethodCall('addOverrideService', [$service]);
            static::decorateServices($container, $service);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param Reference        $reference
     *
     * @return void
     */
    protected static function decorateServices(ContainerBuilder $container, Reference $reference): void
    {
        $serviceName = (string) $reference;
        $definition = $container->findDefinition($serviceName);
        $definition->addMethodCall('setUp', []);
        $overrideServicesCallback = [$serviceName, 'getOverridenServiceNames'];

        foreach ($overrideServicesCallback() as $overridenServiceName) {
            $definition->setDecoratedService($overridenServiceName);
        }
    }
}
