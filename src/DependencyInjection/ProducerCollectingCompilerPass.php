<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProducerCollectingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $collection = $container->getDefinition('app.second_collection');

        $taggedServices = $container->findTaggedServiceIds('producer');

        $services = [];

        foreach ($taggedServices as $id => $tags) {
            $services[] = $container->getDefinition($id);
        }

        $collection->setArgument(0, $services);
    }
}
