<?php

declare(strict_types=1);

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => ['onExceptionEvent', 1000],
        ];
    }

    public function onExceptionEvent(ExceptionEvent $event): void
    {
        if ($event->getRequest()->getPathInfo() !== '/topic2/error') {
            return;
        }

        $response = new Response('An error occurred and the listener intercepted it', 200);
        $response->setCache(['max_age' => 600, 'public' => true]);

        $event->setResponse($response);
        $event->allowCustomResponseCode();

        $event->stopPropagation();
    }
}
