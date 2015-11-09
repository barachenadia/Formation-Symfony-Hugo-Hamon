<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $tree = new TreeBuilder();
        $root = $tree->root('app');

        $root
            ->children()
                ->arrayNode('dictionaries')
                    ->info('The list of dictionaries')
                    ->example('[ /path/to/file.txt, /path/to/file.xml, ... ]')
                    ->requiresAtLeastOneElement()
                    ->performNoDeepMerging()
                    ->isRequired()
                    ->prototype('scalar')
                        ->validate()
                            ->ifTrue(function ($path) {
                                return !is_readable($path);
                            })
                            ->thenInvalid('File %s does not exist or is not readable.')
                        ->end()
                    ->end()
                ->end()
                ->integerNode('word_length')
                    ->info('The default word length')
                    ->example('6')
                    ->min(3)
                    ->max(15)
                    ->defaultValue(8)
                ->end()
                ->enumNode('difficulty')
                    ->info('The difficulty level')
                    ->example('medium')
                    ->defaultValue('easy')
                    ->values([ 'easy', 'medium', 'hard' ])
                ->end()
            ->end()
        ;

        return $tree;
    }
}
