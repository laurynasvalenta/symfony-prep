<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProducerCollectingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
    }
}
