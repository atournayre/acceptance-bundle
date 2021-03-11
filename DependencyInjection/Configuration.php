<?php

namespace Atournayre\AcceptanceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('atournayre_acceptance');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('is_enabled')->defaultTrue()->end()
                ->scalarNode('start_date_time')->defaultNull()->end()
                ->scalarNode('end_date_time')->defaultNull()->end()
                ->scalarNode('template')
                    ->defaultValue('@AtournayreAcceptance/index.html.twig')
                ->end()
                ->scalarNode('templateError')
                    ->defaultValue('@AtournayreAcceptance/error.html.twig')
                ->end()
                ->scalarNode('noEndDateMessage')
                    ->defaultValue('No end date specified, please add it in config.')
                ->end()
                ->scalarNode('dateConversionErrorMessage')
                    ->defaultValue('Oops, an error occurs during conversion of "%s" into date time.')
                ->end()
            ->end();


        return $treeBuilder;
    }
}
