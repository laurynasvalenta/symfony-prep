<?php

namespace App;

use App\DependencyInjection\ClockInjectingCompilerPass;
use App\DependencyInjection\LoggerRemovingExtension;
use App\DependencyInjection\ProducerCollectingCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ProducerCollectingCompilerPass());
        $container->addCompilerPass(new ClockInjectingCompilerPass());

        $container->registerExtension(new LoggerRemovingExtension());
    }
}
