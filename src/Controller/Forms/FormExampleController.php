<?php

declare(strict_types=1);

namespace App\Controller\Forms;

use App\Form\AnotherExampleFormType;
use App\Form\ExampleFormType;
use App\Form\WorkingHoursType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class FormExampleController extends AbstractController
{
    public function __construct(
        private FormFactoryInterface $formFactory,
    ) {
    }

    #[Route('/topic6/example-form1')]
    public function formIsDisplayed(Request $request): Response
    {
        $form = $this->formFactory->create(ExampleFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $content = 'Submitted example form with name ' . $form->get('name')->getData() . '.';
        }

        return $this->render('topic6/example_form.html.twig', [
            'form' => $form,
            'content' => $content ?? '',
        ]);
    }

    #[Route('/topic6/example-form2')]
    public function formIsDisplayed2(): Response
    {
        $form = $this->formFactory
            ->createBuilder(ExampleFormType::class)
            ->add('verySpecialSubmitButton', SubmitType::class)
            ->getForm();

        return $this->render('topic6/example_form.html.twig', [
            'form' => $form?->createView(),
        ]);
    }

    #[Route('/topic6/example-form3')]
    public function formIsDisplayed3(): Response
    {
        $form = $this->createForm(ExampleFormType::class);

        return $this->render('topic6/example_form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/topic6/twig-form1')]
    public function formIsDisplayed4(): Response
    {
        $form = $this->createForm(ExampleFormType::class);

        return $this->render('topic6/form_theme_customization1.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/topic6/twig-form2')]
    public function formIsDisplayed5(): Response
    {
        $form = $this->createForm(AnotherExampleFormType::class)->createView();

        return $this->render('topic6/form_theme_customization2.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/topic6/working-hours-type-used-in-form')]
    public function workingHours(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('workingHours', WorkingHoursType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        return $this->render('topic6/example_form.html.twig', [
            'form' => $form,
        ]);
    }
}
