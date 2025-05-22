<?php

declare(strict_types=1);

namespace App\Subscriber;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Demonstrates console events handling through an event subscriber.
 */
class ConsoleEventSubscriber implements EventSubscriberInterface
{
    public static bool $commandEventTriggered = false;
    public static bool $terminateEventTriggered = false;
    public static bool $errorEventTriggered = false;

    public static function getSubscribedEvents(): array
    {
        return [];
    }

    public function onConsoleCommand(): void
    {
        self::$commandEventTriggered = true;
    }

    public function onConsoleTerminate(): void
    {
        self::$terminateEventTriggered = true;
    }

    public function onConsoleError(ConsoleErrorEvent $event): void
    {
        $event->stopPropagation();

        self::$errorEventTriggered = true;
    }
} 