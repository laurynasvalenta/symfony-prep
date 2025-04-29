<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

use Psr\Log\LoggerInterface;

class Connector
{
    private ?LoggerInterface $logger = null;

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    public function setLogger(?LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
