<?php

declare(strict_types=1);

namespace App\Tests\Topic6;

use App\Form\Extension\ReadOnlyFieldExtension;
use App\Form\WorkingHoursType;
use App\Model\WorkingHours;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Form\Test\TypeTestCase;

class WorkingHoursTypeTest extends TypeTestCase
{
    private ClockInterface $clock;

    public function setUp(): void
    {
        $this->clock = new MockClock(new DateTimeImmutable('2025-10-12 05:00:00'));

        parent::setUp();
    }

    #[Test]
    public function emptyFormDataIsThereByDefault(): void
    {
        $form = $this->factory->create(WorkingHoursType::class);

        $this->assertEmpty($form->getData());
    }

    #[Test]
    public function dataCanBeSubmittedAndTransformed(): void
    {
        $form = $this->factory->create(WorkingHoursType::class);

        $form->submit('09:00 - 17:00');

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(new WorkingHours(900, 1700), $form->getData());
    }

    #[Test]
    public function invalidDataCannotBeTransformed(): void
    {
        $form = $this->factory->create(WorkingHoursType::class);

        $form->submit('invalid data');

        $this->assertFalse($form->isSynchronized());
        $this->assertNull($form->getData());
    }

    #[Test]
    public function workingHoursCannotBeChangedDuringWorkingHours(): void
    {
        $this->clock->modify('+5 hours');

        $view = $this->factory
            ->create(WorkingHoursType::class, new WorkingHours(900, 1700))
            ->createView();

        $this->assertTrue($view->vars['attr']['readonly']);
    }

    #[Test]
    public function workingHoursCanBeChangeOutsideWorkingHours(): void
    {
        $view = $this->factory
            ->create(WorkingHoursType::class, new WorkingHours(900, 1700))
            ->createView();

        $this->assertEmpty($view->vars['attr']['readonly'] ?? null);
    }

    protected function getTypeExtensions(): array
    {
        return [
            new ReadOnlyFieldExtension($this->clock),
        ];
    }
}
