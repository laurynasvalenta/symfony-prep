<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Service\DependencyInjectionTopic\Aggregator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ClockInjectingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(Aggregator::class);

        $definition->setArgument(0, $container->getDefinition('clock'));
    }
}
