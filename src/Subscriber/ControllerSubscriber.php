<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Controller\SymfonyArchitecture\SymfonyArchitectureController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ControllerSubscriber implements EventSubscriberInterface
{
    public function __construct(private SymfonyArchitectureController $controller)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => ['onControllerEvent', 1000],
        ];
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        if ($event->getRequest()->headers->get('X-INTERCEPT') !== 'request2') {
            return;
        }

        $event->setController([$this->controller, 'page2']);
    }
}
