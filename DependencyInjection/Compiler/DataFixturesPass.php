<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler;

use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DataFixturesPass
 *
 * @package   RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class DataFixturesPass implements CompilerPassInterface
{
    public const DATA_FIXTURE_TAG = 'rich_congress.unit_bundle.data_fixture';

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(FixturesManager::class)) {
            return;
        }

        $definition = $container->findDefinition(FixturesManager::class);
        $taggedServices = $container->findTaggedServiceIds(self::DATA_FIXTURE_TAG);
        $definition->addMethodCall('setFixturesClasses', [$taggedServices]);
    }
}
