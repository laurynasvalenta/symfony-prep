<?php

declare(strict_types=1);

namespace App\Controller\Forms;

use App\Form\AnotherExampleFormType;
use App\Form\ExampleFormType;
use App\Form\WorkingHoursType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class FormExampleController
{
    #[Route('/topic6/example-form1')]
    public function formIsDisplayed(): Response
    {
        return new Response();
    }

    #[Route('/topic6/example-form2')]
    public function formIsDisplayed2(): Response
    {
        return new Response();
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
