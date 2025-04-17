<?php

declare(strict_types=1);

namespace App\Controller\Forms;

use App\Enum\ExampleEnum;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UlidType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\UuidType;
use Symfony\Component\Form\Extension\Core\Type\WeekType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
class FormShowcaseController
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private Environment $environment,
    ) {
    }

    #[Route('/forms')]
    public function forms(): Response
    {
        $form = $this->formFactory->createBuilder()
            ->add('text', TextType::class, [
                'required' => false,
            ])
            ->add('textarea', TextareaType::class, [
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'required' => false,
            ])
            ->add('integer', IntegerType::class, [
                'required' => false,
            ])
            ->add('money', MoneyType::class, [
                'required' => false,
            ])
            ->add('number', NumberType::class, [
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'required' => false,
            ])
            ->add('percent', PercentType::class, [
                'required' => false,
            ])
            ->add('search', SearchType::class, [
                'required' => false,
            ])
            ->add('url', UrlType::class, [
                'required' => false,
            ])
            ->add('rangeType', RangeType::class)
            ->add('tel', TelType::class, [
                'required' => false,
            ])
            ->add('color', ColorType::class)
            ->add('choice', ChoiceType::class, [
                'choices' => [
                    'Choice 1' => 'choice1',
                    'Choice 2' => 'choice2',
                    'Choice 3' => 'choice3',
                ],
            ])
            ->add('enum', EnumType::class, [
                'class' => ExampleEnum::class,
            ])
            ->add('country', CountryType::class)
            ->add('language', LanguageType::class)
            ->add('locale', LocaleType::class)
            ->add('timezone', TimezoneType::class)
            ->add('currency', CurrencyType::class)
            ->add('date', DateType::class, [
                'required' => false,
            ])
            ->add('dateInterval', DateIntervalType::class)
            ->add('dateTime', DateTimeType::class, [
                'required' => false,
            ])
            ->add('time', TimeType::class)
            ->add('birthday', BirthdayType::class)
            ->add('week', WeekType::class)
            ->add('checkbox', CheckboxType::class)
            ->add('file', FileType::class)
            ->add('radio', RadioType::class)
            ->add('uuid', UuidType::class)
            ->add('ulid', UlidType::class)
            ->add('hidden', HiddenType::class)
            ->add('repeated', RepeatedType::class, [
                'type' => TextType::class,
                'first_options' => ['label' => 'First Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('button', ButtonType::class)
            ->add('reset', ResetType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        return new Response($this->environment->render('topic6/forms_showcase.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
