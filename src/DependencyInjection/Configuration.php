<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('waaz_sylius_tnt');
        $rootNode = $treeBuilder->getRootNode();
        $this->addGlobalSection($rootNode);

        return $treeBuilder;
    }

    private function addGlobalSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('username')
                    ->defaultValue('login')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('password')
                    ->defaultValue('password')
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('sandbox')
                    ->defaultTrue()
                ->end()
                ->enumNode('weight_unit')
                    ->cannotBeEmpty()
                    ->values(['kg', 'g'])
                    ->defaultValue('g')
                ->end()
                ->scalarNode('city_select_classes')
                    ->defaultValue('ui dropdown')
                ->end()
            ->end()
        ;
    }
}
