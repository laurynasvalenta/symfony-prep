<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OnlyOnWeekdayValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
    }
}
