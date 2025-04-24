<?php


declare(strict_types=1);

namespace App\Controller\DataValidation;

use App\Enum\CarFormValidationGroupEnum;
use App\Enum\OrderFormValidationGroupEnum;
use App\Model\Car;
use App\Model\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DataValidationController extends AbstractController
{
    #[Route('/topic7/car-form/{carFormValidationGroupEnum}')]
    public function carForm(Request $request, CarFormValidationGroupEnum $carFormValidationGroupEnum): Response
    {
        $data = new Car();

        $form = $this->createFormBuilder($data, ['validation_groups' => [$carFormValidationGroupEnum->value]])
            ->add('brand')
            ->add('fuelType')
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        return $this->render('topic6/example_form.html.twig', ['form' => $form]);
    }

    #[Route('/topic7/order-form/{orderFormValidationGroupEnum}')]
    public function orderForm(Request $request, OrderFormValidationGroupEnum $orderFormValidationGroupEnum): Response
    {
        $data = new Order();

        $form = $this->createFormBuilder($data, ['validation_groups' => [$orderFormValidationGroupEnum->value]])
            ->add('productName')
            ->add('quantity')
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        return $this->render('topic6/example_form.html.twig', ['form' => $form]);
    }
}
