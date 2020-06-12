<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler;

use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class PublicServicesPass
 *
 * @package   RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class PublicServicesPass extends AbstractCompilerPass
{
    public const PRIORITY = -1000;

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $publicServicesConfig = $container->hasParameter('rich_congress_unit.public_services')
            ? $container->getParameter('rich_congress_unit.public_services')
            : [];

        $defaultStubsConfig = $container->hasParameter('rich_congress_unit.default_stubs')
            ? $container->getParameter('rich_congress_unit.default_stubs')
            : [];

        $publicServices = array_unique(
            array_merge($publicServicesConfig, array_keys($defaultStubsConfig))
        );

        foreach ($container->getDefinitions() as $id => $definition) {
            if ($id === null || $definition->isPublic() || $definition->isAbstract()) {
                continue;
            }

            $isAnAppService = self::startsWith($id, 'App\\') || self::contains($id, '\\App\\');

            if($isAnAppService || \in_array($id, $publicServices, true)) {
                $definition->setPublic(true);
            }
        }
    }

    /**
     * @param string $string
     * @param string $startString
     *
     * @return boolean
     */
    protected static function startsWith(string $string, string $startString): bool
    {
        return strpos($string, $startString) === 0;
    }

    /**
     * @param string $string
     * @param string $substring
     *
     * @return boolean
     */
    protected static function contains(string $string, string $substring): bool
    {
        return strpos($string, $substring) !== false;
    }
}
