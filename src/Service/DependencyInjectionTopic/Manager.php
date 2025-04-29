<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class Manager
{
    private ?RouterInterface $router = null;
    public ?Environment $twig = null;

    public function __construct(private ?LoggerInterface $logger = null)
    {
    }

    public function setRouter(?RouterInterface $router): void
    {
        $this->router = $router;
    }

    public function getRouter(): ?RouterInterface
    {
        return $this->router;
    }

    public function getTwig(): ?Environment
    {
        return $this->twig;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
}
