<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\WorkingHours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkingHoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(
            new CallbackTransformer(
                function (?WorkingHours $workingHours) {
                    if ($workingHours === null) {
                        return null;
                    }

                    $openingHours = $this->formatHours($workingHours->getOpeningTime());
                    $closingHours = $this->formatHours($workingHours->getClosingTime());

                    return $openingHours . ' - ' . $closingHours;
                },
                function (string $value) {
                    if (!preg_match('/^(\d{2}):(\d{2})\s-\s(\d{2}):(\d{2})$/', $value)) {
                        throw new TransformationFailedException('Invalid format. Expected HH:MM - HH:MM');
                    }

                    $times = explode(' - ', $value);
                    $openingTime = explode(':', $times[0]);
                    $closingTime = explode(':', $times[1]);

                    return new WorkingHours(
                        intval($openingTime[0]) * 100 + intval($openingTime[1]),
                        intval($closingTime[0]) * 100 + intval($closingTime[1])
                    );
                }
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    public function getParent(): ?string
    {
        return TextType::class;
    }

    function formatHours(?int $openingHours): string
    {
        if ($openingHours === null) {
            return  sprintf('%02d:%02d', intval($openingHours / 100), $openingHours % 100);
        }

        return '';
    }
}
