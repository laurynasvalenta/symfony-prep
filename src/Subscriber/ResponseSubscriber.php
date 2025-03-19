<?php

declare(strict_types=1);

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseEvent::class => ['onResponseEvent', 1000],
        ];
    }

    public function onResponseEvent(ResponseEvent $event): void
    {
        if ($event->getRequest()->getPathInfo() !== '/topic2/page5') {
            return;
        }

        $response = $event->getResponse();

        $event->setResponse(new Response($response->getContent() . ' with content added by a listener'));
    }
}
