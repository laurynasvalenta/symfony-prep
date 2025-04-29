<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;
use Throwable;

class ServiceWithMultipleDependencies
{
    public function __construct(
        private ?ContainerInterface $locator = null
    ) {
    }

    public function getLogger(): ?LoggerInterface
    {
        try {
            return $this->locator?->get(LoggerInterface::class);
        } catch (Throwable) {
            return null;
        }
    }

    public function getClock(): ?ClockInterface
    {
        try {
            return $this->locator?->get(ClockInterface::class);
        } catch (Throwable) {
            return null;
        }
    }

    public function getRouter(): ?RouterInterface
    {
        try {
            return $this->locator?->get('router');
        } catch (Throwable) {
            return null;
        }
    }
}
