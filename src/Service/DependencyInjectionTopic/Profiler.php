<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

class Profiler
{
    public function __construct(
        private string $input = ''
    ) {
    }

    public function getInput(): string
    {
        return $this->input;
    }
}
