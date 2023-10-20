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
        $treeBuilder = new TreeBuilder('waaz_sylius_dpd');
        $rootNode = $treeBuilder->getRootNode();
        $this->addGlobalSection($rootNode);

        return $treeBuilder;
    }

    private function addGlobalSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('api_pickup_points_key')
                    ->defaultValue('%env(resolve:DPD_API_PICKUP_POINTS_KEY)%')
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
            ->end()
        ;
    }
}
