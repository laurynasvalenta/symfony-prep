<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

class OuterTrackerDecorator implements TrackerInterface
{
    public function __construct(private ?TrackerInterface $tracker = null)
    {
    }


    public function track(): string
    {
        return __CLASS__ . ' ' . $this->tracker->track();
    }
}
