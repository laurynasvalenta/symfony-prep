<?php

declare(strict_types=1);

namespace App\Tests\Topic07;

use App\Validator\Constraints\OnlyOnWeekday;
use App\Validator\Constraints\OnlyOnWeekdayValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/*
 * This is a demonstration test for Symfony Certification Topic 7 (Data Validation).
 */
class Topic07OnlyOnWeekdayConstraintTest extends TestCase
{
    private ValidatorInterface $validator;
    private ClockInterface $clock;

    public function setUp(): void
    {
        parent::setUp();

        $this->clock = new MockClock('2025-04-23 03:40:00');

        $validator = new OnlyOnWeekdayValidator($this->clock);

        $validatorFactory = new ConstraintValidatorFactory([
            OnlyOnWeekdayValidator::class => $validator,
        ]);

        $this->validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory($validatorFactory)
            ->getValidator();
    }

    #[Test]
    public function valueFalseIsAllowedEvenIfNoneOfTheWeekdaysAreWhitelisted(): void
    {
        $violations = $this->validator->validate(false, new OnlyOnWeekday());

        self::assertCount(0, $violations);
    }

    #[Test]
    public function valueTrueIsConsideredInvalidIfNoWeekdaysAreWhitelisted(): void
    {
        $violations = $this->validator->validate(true, new OnlyOnWeekday());

        self::assertCount(1, $violations);
        self::assertSame('Value True is not allowed on Wednesday.', $violations->get(0)->getMessage());
    }

    #[Test]
    public function valueTrueIsConsideredValidIfWeekdayIsWhitelisted(): void
    {
        $this->clock->modify('2025-04-22 03:40:00');

        $constraint = new OnlyOnWeekday();
        $constraint->setAllowedWeekdays('Monday', 'Tuesday');

        $violations = $this->validator->validate(true, $constraint);

        self::assertCount(0, $violations);
    }
}
