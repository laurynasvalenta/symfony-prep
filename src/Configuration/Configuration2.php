<?php

declare(strict_types=1);

namespace App\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration2 implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('config');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->stringNode('type')
                    ->validate()
                        ->ifNotInArray(['pdf', 'jpg'])
                        ->then(fn($m) => sprintf('Other type: %s', $m))
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
