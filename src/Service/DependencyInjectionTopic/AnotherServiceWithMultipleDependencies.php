<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;

class AnotherServiceWithMultipleDependencies
{
    #[SubscribedService]
    public function getClock(): ?ClockInterface
    {
        return null;
    }

    #[SubscribedService]
    public function getLogger(): ?LoggerInterface
    {
        return null;
    }
}
