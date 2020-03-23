<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public const CONFIG_NODE = 'rich_congres_unit';

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::CONFIG_NODE);
        $rootNode = \method_exists(TreeBuilder::class, 'getRootNode')
            ? $treeBuilder->getRootNode()
            : $treeBuilder->root(self::CONFIG_NODE);

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

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
