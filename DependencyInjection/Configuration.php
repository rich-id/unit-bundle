<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection;

use RichCongress\BundleToolbox\Configuration\AbstractConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration extends AbstractConfiguration
{
    public const CONFIG_NODE = 'rich_congress_unit';

    /**
     * @param ArrayNodeDefinition $rootNode
     *
     * @return void
     */
    protected function buildConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('db_cache')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultValue(true)->end()
                        ->integerNode('lifetime')->defaultValue(60)->end()
                    ->end()
                ->end()

                ->arrayNode('default_stubs')
                    ->children()
                        ->booleanNode('logger')->defaultFalse()->end()
                    ->end()
                ->end()

                ->arrayNode('public_services')
                    ->example(['logger', 'App/Repository/UserRepository'])
                    ->scalarPrototype()->end()
                ->end()

                ->arrayNode('test_roles')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('key')
                    ->example(['NotLogged' => '', 'Admin' => 'user_1'])
                    ->scalarPrototype()->end()
                ->end()
            ->end();
    }
}
