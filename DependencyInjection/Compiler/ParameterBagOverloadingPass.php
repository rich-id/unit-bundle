<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler;

use RichCongress\Bundle\UnitBundle\Stubs\ParameterBagStub;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ParameterBagOverloadingPass
 *
 * @package   RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ParameterBagOverloadingPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(ParameterBagStub::class)) {
            return;
        }

        $definition = $container->findDefinition(ParameterBagStub::class);
        $definition->addMethodCall('overrideParameters');
    }
}
