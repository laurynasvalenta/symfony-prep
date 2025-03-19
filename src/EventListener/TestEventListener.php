<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\EventDispatcher\Event;

#[AsEventListener(event: 'test_event')]
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
