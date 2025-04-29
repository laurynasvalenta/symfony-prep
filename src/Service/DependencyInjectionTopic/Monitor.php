<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

use Psr\Log\LoggerInterface;

class Monitor
{
    public function __construct(
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
}
