<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

class Tracker implements TrackerInterface
{
    public function track(): string
    {
        return __CLASS__;
    }
}
