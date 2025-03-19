<?php

declare(strict_types=1);

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ControllerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
