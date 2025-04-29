<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

class Collection
{
    public function __construct(private ?iterable $items = null)
    {
    }

    public function getItems(): array
    {
        return iterator_to_array($this->items ?? []);
    }
}
