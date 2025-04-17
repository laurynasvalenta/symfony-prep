<?php

declare(strict_types=1);

namespace App\Model;

class WorkingHours
{
    public function __construct(
        private ?int $openingTime,
        private ?int $closingTime,
    ) {
    }

    public function getOpeningTime(): int
    {
        return $this->openingTime;
    }

    public function getClosingTime(): int
    {
        return $this->closingTime;
    }
}
