<?php

namespace App;

use App\DependencyInjection\ClockInjectingCompilerPass;
use App\DependencyInjection\LoggerRemovingExtension;
use App\DependencyInjection\ProducerCollectingCompilerPass;
use App\Service\DependencyInjectionTopic\SpecialAttribute;
use App\Service\DependencyInjectionTopic\SpecialInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ChildDefinition;
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

        $container->registerForAutoconfiguration(SpecialInterface::class)->addTag('special_tag1');
        $container->registerAttributeForAutoconfiguration(
            SpecialAttribute::class,
            static function (ChildDefinition $definition): void {
                $definition->addTag('special_tag2');
            }
        );
    }
}
