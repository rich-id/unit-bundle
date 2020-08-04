<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection;

use RichCongress\BundleToolbox\Configuration\AbstractConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

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
        $children = $rootNode->children();

        $this->buildDbCacheNode($children);
        $this->buildDefaultStubsNode($children);
        $this->buildPublicServicesNode($children);
        $this->buildTestRolesNode($children);

        $children->end();
    }

    /**
     * @param NodeBuilder $nodeBuilder
     *
     * @return NodeBuilder
     */
    protected function buildDbCacheNode(NodeBuilder $nodeBuilder): NodeBuilder
    {
        return $nodeBuilder
            ->arrayNode('db_cache')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enable')->defaultValue(true)->end()
                    ->integerNode('lifetime')->defaultValue(60)->end()
                ->end()
            ->end();
    }

    /**
     * @param NodeBuilder $nodeBuilder
     *
     * @return NodeBuilder
     */
    protected function buildDefaultStubsNode(NodeBuilder $nodeBuilder): NodeBuilder
    {
        return $nodeBuilder
            ->arrayNode('default_stubs')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('logger')->defaultFalse()->end()
                ->end()
            ->end();
    }

    /**
     * @param NodeBuilder $nodeBuilder
     *
     * @return NodeBuilder
     */
    protected function buildPublicServicesNode(NodeBuilder $nodeBuilder): NodeBuilder
    {
        return $nodeBuilder
            ->arrayNode('public_services')
                ->defaultValue([])
                ->example(['logger', 'App/Repository/UserRepository'])
                ->scalarPrototype()->end()
            ->end();
    }

    /**
     * @param NodeBuilder $nodeBuilder
     *
     * @return NodeBuilder
     */
    protected function buildTestRolesNode(NodeBuilder $nodeBuilder): NodeBuilder
    {
        return $nodeBuilder
            ->arrayNode('test_roles')
                ->normalizeKeys(false)
                ->useAttributeAsKey('key')
                ->defaultValue([])
                ->example(['NotLogged' => '', 'Admin' => 'user_1'])
                ->scalarPrototype()->end()
            ->end();
    }
}
