<?php

declare(strict_types=1);

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class Topic3Subscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.view' => 'onKernelView',
        ];
    }

    public function onKernelView(ViewEvent $event): void
    {
        if ($event->getRequest()->getPathInfo() !== '/topic3/twig2') {
            return;
        }

        if ($event->getResponse() !== null) {
            throw new \LogicException('The response should be null at this point.');
        }

        $event->setResponse(new Response($event->getControllerResult()));
    }
}
