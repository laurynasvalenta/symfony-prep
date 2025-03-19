<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Controller\SymfonyArchitecture\SymfonyArchitectureController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ControllerArgumentsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ControllerArgumentsEvent::class => ['onControllerArgumentsEvent', 1000],
        ];
    }

    public function onControllerArgumentsEvent(ControllerArgumentsEvent $event): void
    {
        if ($event->getRequest()->attributes->get('_controller') !== SymfonyArchitectureController::class . '::page3') {
            return;
        }

        $event->setArguments(['Argument injected via event listener']);
    }
}
