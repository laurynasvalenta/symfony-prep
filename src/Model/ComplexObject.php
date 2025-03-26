<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;

class ComplexObject
{
    public function __construct(
        private string $name,
        private DateTime $date,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }
}
