<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler;

use RichCongress\Bundle\UnitBundle\TestCase\Internal\WebTestCase;
use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
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

        $definition = $container->findDefinition(OverrideServicesUtility::class);
        $taggedServices = $container->findTaggedServiceIds(self::OVERRIDE_SERVICE_TAG);

        foreach ($taggedServices as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addOverrideService', [
                    new Reference($serviceId),
                    $attributes['priority'] ?? 0
                ]);
            }
        }
    }
}
