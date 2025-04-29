<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Service\DependencyInjectionTopic\Monitor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Throwable;

class LoggerRemovingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->setParameter('new_parameter', 'value');

        $this->removeLogger($container);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    protected function removeLogger(ContainerBuilder $container): void
    {
        try {
            $container->findDefinition(Monitor::class)->setArgument('$logger', null);
        } catch (Throwable) {
        }
    }
}
