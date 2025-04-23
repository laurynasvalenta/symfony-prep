<?php

declare(strict_types=1);

namespace App\Model;

class ExampleModel
{
    public function __construct(
        private readonly mixed $mainProperty,
        private readonly mixed $secondProperty = null,
    ) {
    }

    public function getMainProperty(): mixed
    {
        return $this->mainProperty;
    }

    public function getSecondProperty(): mixed
    {
        return $this->secondProperty;
    }
}
