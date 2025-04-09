<?php

declare(strict_types=1);

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class ViewSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ViewEvent::class => ['onViewEvent', 1000],
        ];
    }

    public function onViewEvent(ViewEvent $event): void
    {
        $content = $event->getControllerResult();

        if (is_string($content)) {
            $event->setResponse(new Response($content));
        }
    }
}
