<?php

declare(strict_types=1);

namespace App\Tests\Topic7;

use App\Model\AnotherExampleModel;
use App\Model\ExampleModel;
use App\Model\TraversableExample;
use App\Model\User;
use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

/*
 * This is a demonstration test for Symfony Certification Topic 7 (Data Validation).
 */
class Topic7Test extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    /**
     * @see config/validator/Car.yaml
     * @see config/validator/Vehicle.yaml
     */
    #[Test]
    public function carFormIsSubmittedWithValidInputs(): void
    {
        $this->client->request('GET', '/topic7/car-form/Default');

        $this->client->submitForm(
            'form[submit]',
            ['form[brand]' => 'VW', 'form[fuelType]' => 'Petrol']
        );

        self::assertResponseIsSuccessful();
        self::assertSelectorTextNotContains('body', 'Vehicle brand should not be blank.');
        self::assertSelectorTextNotContains('body', 'Car fuel type should not be blank.');
    }


    /**
     * @see config/validator/Car.yaml
     * @see config/validator/Vehicle.yaml
     */
    #[Test]
    public function carFormIsValidatedWithDefaultGroup(): void
    {
        $this->client->request('GET', '/topic7/car-form/Default');

        $this->client->submitForm(
            'form[submit]',
            ['form[brand]' => '', 'form[fuelType]' => '']
        );

        self::assertResponseIsUnprocessable();
        self::assertSelectorTextContains('body', 'Vehicle brand should not be blank.');
        self::assertSelectorTextContains('body', 'Car fuel type should not be blank.');
    }

    /**
     * @see config/validator/Car.yaml
     * @see config/validator/Vehicle.yaml
     */
    #[Test]
    public function carFormIsValidatedWithCarGroup(): void
    {
        $this->client->request('GET', '/topic7/car-form/Car');

        $this->client->submitForm(
            'form[submit]',
            ['form[brand]' => '', 'form[fuelType]' => '']
        );

        self::assertResponseIsUnprocessable();
        self::assertSelectorTextContains('body', 'Vehicle brand should not be blank.');
        self::assertSelectorTextContains('body', 'Car fuel type should not be blank.');
    }

    /**
     * @see config/validator/Car.yaml
     * @see config/validator/Vehicle.yaml
     */
    #[Test]
    public function carFormIsValidatedWithVehicleGroup(): void
    {
        $this->client->request('GET', '/topic7/car-form/Vehicle');

        $this->client->submitForm('form[submit]', ['form[brand]' => '', 'form[fuelType]' => '']);

        self::assertResponseIsUnprocessable();
        self::assertSelectorTextContains('body', 'Vehicle brand should not be blank.');
        self::assertSelectorTextNotContains('body', 'Car fuel type should not be blank.');
    }

    /**
     * @see src/Model/Order.php
     */
    #[Test]
    public function lightValidationIsExecutedFirst(): void
    {
        $this->client->request('GET', '/topic7/order-form/Default');

        $this->client->submitForm('form[submit]', ['form[productName]' => '', 'form[quantity]' => '']);

        self::assertResponseIsUnprocessable();
        self::assertSelectorTextContains('body', 'You must provide product name.');
        self::assertSelectorTextContains('body', 'You must provide order quantity.');
        self::assertSelectorTextNotContains('body', 'Your order exceeds the stock.');
    }

    /**
     * @see src/Model/Order.php
     */
    #[Test]
    public function heavyValidationIsExecutedIfLightValidationPasses(): void
    {
        $this->client->request('GET', '/topic7/order-form/Default');

        $this->client->submitForm('form[submit]', ['form[productName]' => 'Name', 'form[quantity]' => '1001']);

        self::assertResponseIsUnprocessable();
        self::assertSelectorTextNotContains('body', 'You must provide product name.');
        self::assertSelectorTextNotContains('body', 'You must provide order quantity.');
        self::assertSelectorTextContains('body', 'Your order exceeds the stock.');
    }
}
