<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

use Psr\Log\LoggerInterface;

class Handler implements HandlerInterface
{
    public static bool $initiated = false;

    public function __construct(
        private readonly ?LoggerInterface $logger = null
    ) {
        self::$initiated = true;
    }

    public function handle(): void
    {
        $this->logger->info('Handler is handling the request.');
    }
}
