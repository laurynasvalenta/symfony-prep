<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

class Producer
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
