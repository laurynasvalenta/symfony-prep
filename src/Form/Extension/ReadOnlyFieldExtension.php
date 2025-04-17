<?php

declare(strict_types=1);

namespace App\Form\Extension;

use App\Form\WorkingHoursType;
use Psr\Clock\ClockInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ReadOnlyFieldExtension extends AbstractTypeExtension
{
    public function __construct(
        private ClockInterface $clock
    ) {
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
    }

    public static function getExtendedTypes(): iterable
    {
        yield WorkingHoursType::class;
    }
}
