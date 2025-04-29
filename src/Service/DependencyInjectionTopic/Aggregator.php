<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

use Psr\Clock\ClockInterface;

class Aggregator
{
    public function __construct(private ?ClockInterface $clock = null)
    {
    }

    public function getClock(): ?ClockInterface
    {
        return $this->clock;
    }
}
