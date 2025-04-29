<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

interface TrackerInterface
{
    public function track(): string;
}
