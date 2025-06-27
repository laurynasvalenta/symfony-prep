<?php

declare(strict_types=1);

namespace App\Tests\Topic06;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/*
 * This is a demonstration test for Symfony Certification Topic 6 (Forms).
 */
class Topic06Test extends WebTestCase
{
    /**
     * Use `topic6/example_form.html.twig` template.
     *
     * @see src/Controller/Forms/FormExampleController.php
     */
    #[Test]
    #[DataProvider('formObjectCanBeCreatedProvider')]
    public function formObjectCanBeCreated(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        static::assertResponseIsSuccessful();
        static::assertSelectorExists('form[name="example_form"]');
    }

    /**
     * Use `topic6/example_form.html.twig` template.
     *
     * @see src/Controller/Forms/FormExampleController.php
     */
    #[Test]
    public function formCanBeHandled(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topic6/example-form1');

        $client->submitForm('Submit', [
            'example_form[name]' => 'Bob',
        ]);

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('h1', 'Submitted example form with name Bob.');
    }

    /**
     * @see src/Form/ExampleFormType.php
     */
    #[Test]
    public function formIsValidated(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topic6/example-form1');

        $client->submitForm('Submit', [
            'example_form[name]' => '',
        ]);

        static::assertResponseIsUnprocessable();
        static::assertSelectorTextContains('body', 'Name should not be blank.');
    }

    /**
     * Make this test pass by setting the form theme to 'foundation_6_layout.html.twig'.
     *
     * @see config/packages/twig.yaml
     */
    #[Test]
    public function formThemeCanBeChangedViaTwigConfiguration(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topic6/example-form1');

        static::assertResponseIsSuccessful();
        static::assertSelectorExists('button.button');
    }

    /**
     * Make this test pass by setting the form theme to 'bootstrap_5_layout.html.twig'.
     *
     * @see templates/topic6/form_theme_customization1.html.twig
     */
    #[Test]
    public function formThemeCanBeChangedInTwig(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topic6/twig-form1');

        static::assertResponseIsSuccessful();
        static::assertSelectorExists('div.mb-3');
    }

    /**
     * @see templates/topic6/form_theme_customization2.html.twig
     */
    #[Test]
    public function formThemeCanBeCustomizedDirectlyInTemplate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topic6/twig-form2');

        static::assertResponseIsSuccessful();
        static::assertSelectorCount(249, 'div.every-choice-in-its-own-div');
    }

    public static function formObjectCanBeCreatedProvider(): iterable
    {
        yield 'Form object can be created via injected factory' => ['/topic6/example-form1'];
        yield 'Form object can be created via form builder' => ['/topic6/example-form2'];
        yield 'Form object can be created via AbstractController method' => ['/topic6/example-form3'];
    }
}
