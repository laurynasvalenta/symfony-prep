<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

class Factory
{
    public function createService(): Profiler
    {
        return new Profiler('Service built by service factory.');
    }
}
