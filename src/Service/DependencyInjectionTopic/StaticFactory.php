<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

class StaticFactory
{
    public static function createService(): Profiler
    {
        return new Profiler('Service built by static factory.');
    }
}