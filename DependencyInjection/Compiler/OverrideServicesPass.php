<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler;

use RichCongress\Bundle\UnitBundle\Command\DebugOverridenServicesCommand;
use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;
use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class OverrideServicesPass
 *
 * @package   RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class OverrideServicesPass extends AbstractCompilerPass
{
    public const PRIORITY = -100;
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
        $commandDefinition = $container->findDefinition(DebugOverridenServicesCommand::class);
        $taggedServices = $this->findAndSortTaggedServices(self::OVERRIDE_SERVICE_TAG, $container);

        foreach ($taggedServices as $service) {
            $definition = $container->findDefinition($service);
            $class = $definition->getClass();
            $reflectionClass = new \ReflectionClass($class);
            static::decorateServices($definition);

            $utilityDefinition->addMethodCall('addOverrideServiceClass', [$class]);
            $commandDefinition->addMethodCall('addOverrideServiceClass', [$class]);

            if ($reflectionClass->hasMethod('setInnerService')) {
                $innerService = $class . '.inner';
                $definition->addMethodCall('setInnerService', [new Reference($innerService)]);
            }
        }
    }

    /**
     * @param Definition $definition
     *
     * @return void
     */
    protected static function decorateServices(Definition $definition): void
    {
        $overrideServicesCallback = [$definition->getClass(), 'getOverridenServiceNames'];

        foreach ($overrideServicesCallback() as $overridenServiceName) {
            $definition->setDecoratedService($overridenServiceName);
        }
    }
}
