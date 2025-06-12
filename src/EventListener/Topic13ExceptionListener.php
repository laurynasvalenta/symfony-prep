<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class Topic13ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
    }
}
