<?php

declare(strict_types=1);

namespace App\Form\Extension;

use App\Form\WorkingHoursType;
use App\Model\WorkingHours;
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
        $now = $this->clock->now();
        /** @var WorkingHours $data */
        $data = $form->getData();

        $currentTime = (int)$now->format('H') * 100 + (int)$now->format('i');

        if ($currentTime < $data->getOpeningTime() || $currentTime > $data->getClosingTime()) {
            return;
        }

        $view->vars['attr']['readonly'] = true;
    }

    public static function getExtendedTypes(): iterable
    {
        yield WorkingHoursType::class;
    }
}
