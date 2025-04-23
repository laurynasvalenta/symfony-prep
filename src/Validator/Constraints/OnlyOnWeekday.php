<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class OnlyOnWeekday extends Constraint
{
    /**
     * @var string[]
     */
    private array $allowedWeekdays = [];

    public string $message = 'Value True is not allowed on {{ weekday }}.';

    public function getAllowedWeekdays(): array
    {
        return $this->allowedWeekdays;
    }

    public function setAllowedWeekdays(string ...$allowedWeekdays): void
    {
        $this->allowedWeekdays = $allowedWeekdays;
    }
}
