<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Psr\Clock\ClockInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Charset;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class OnlyOnWeekdayValidator extends ConstraintValidator
{
    public function __construct(
        private ClockInterface $clock
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof OnlyOnWeekday) {
            throw new UnexpectedTypeException($constraint, Charset::class);
        }

        if (empty($value)) {
            return;
        }

        $currentWeekday = $this->clock->now()->format('l');

        if (in_array($currentWeekday, $constraint->getAllowedWeekdays())) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ weekday }}', $currentWeekday)
            ->setTranslationDomain('validation_errors')
            ->addViolation();
    }
}
