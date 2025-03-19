<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\Event;

class TestEventListener
{
    public function __construct(
        private readonly Loggerinterface $logger
    ) {
    }

    public function __invoke(Event $event): void
    {
        $this->logger->info('Event triggered');
    }
}
