<?php

declare(strict_types=1);

namespace App\Tests\Topic5;

use App\Controller\TemplatingWithTwig\TemplatingWithTwigController;
use App\TwigExtension\TwigExtension;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Twig\Environment;
use Twig\Error\SyntaxError;
use Twig\Source;

/*
 * This is a demonstration test for Symfony Certification Topic 5 (Templating with Twig).
 */
class Topic5Test extends WebTestCase
{
    use ExpectDeprecationTrait;

    private KernelBrowser $client;
    private Environment $twig;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->twig = static::getContainer()->get('twig');
    }

    /**
     * @see templates/topic5/template1.html.twig
     */
    #[Test]
    public function statementCanBeNotEscaped(): void
    {
        $response = $this->client->request('GET', '/topic5/no-escaping1');

        static::assertResponseIsSuccessful();
        static::assertSame('Bolded text', $response->filter('b')->text());
    }

    /**
     * @see templates/topic5/template2.html.twig
     */
    #[Test]
    public function concatenatedStatementCanBeNotEscaped(): void
    {
        $tokens = $this->getTemplateTokens(__DIR__ . '/../../templates/topic5/template2.html.twig');

        $response = $this->client->request('GET', '/topic5/no-escaping2');

        static::assertCount(
            1,
            array_filter($tokens, static fn ($token) => $token->toEnglish() === 'operator' && $token->getValue() === '~'),
            'Please use concatenation operator "~".'
        );

        static::assertResponseIsSuccessful();
        static::assertCount(2, $response->filter('b'));
    }

    #[Test]
    #[DataProvider('escapingStrategyCanBeChangedProvider')]
    public function escapingStrategyCanBeChanged(string $url): void
    {
        $this->client->request('GET', $url);

        static::assertResponseIsSuccessful();
        static::assertStringContainsString("let example = 'TEST\\u0027';", $this->client->getResponse()->getContent());
    }

    /**
     * @see templates/topic5/template6.html.twig
     */
    #[Test]
    public function templateCanBeInheritedAndModified(): void
    {
        $response = $this->client->request('GET', '/topic5/template-inheritance1');

        $actualBlockContent = array_map('trim', $response->filter('p')->extract(['_text']));
        $expectedBlockContent = [
            'Unchanged block 1 from template6-base.html.twig.',
            'Modified content of block 2',
        ];

        static::assertResponseIsSuccessful();
        static::assertSame($expectedBlockContent, $actualBlockContent);
    }

    /**
     * @see templates/topic5/template7.html.twig
     */
    #[Test]
    public function templateCanBeInheritedAndModifiedMultipleTimes(): void
    {
        $response = $this->client->request('GET', '/topic5/template-inheritance2');

        $actualBlockContent = array_map('trim', $response->filter('p')->extract(['_text']));
        $expectedBlockContent = [
            'Unchanged block 1 from template6-base.html.twig.',
            'Modified content of block 2',
            'Modified content of block 1',
            'Unchanged block 2 from template6-base.html.twig.',
        ];

        static::assertResponseIsSuccessful();
        static::assertSame($expectedBlockContent, $actualBlockContent);
    }

    /**
     * @see templates/topic5/template8.html.twig
     */
    #[Test]
    public function templateCanBeIncludedIntoAnotherOne(): void
    {
        $response = $this->client->request('GET', '/topic5/template-include');

        static::assertResponseIsSuccessful();
        static::assertSame('The value is 42.', $response->text());
    }

    /**
     * @see templates/topic5/template9.html.twig
     */
    #[Test]
    public function controllerCanBeRenderedFromTemplate(): void
    {
        TemplatingWithTwigController::$valueToOutput = microtime();

        $response = $this->client->request('GET', '/topic5/controller-rendering');

        static::assertResponseIsSuccessful();
        static::assertSame(
            'Value generated by a controller: ' . TemplatingWithTwigController::$valueToOutput,
            $response->text()
        );
    }

    /**
     * @see templates/topic5/template10.html.twig
     */
    #[Test]
    public function listOfCountriesIsDisplayed(): void
    {
        $response = $this->client->request('GET', '/topic5/list-of-countries');

        $expectedList = [
            '6. New Zealand',
            '5. Finland',
            '4. Indonesia',
            '3. Jordan',
            '2. Ireland',
            'Last. South Korea',
        ];

        $actualList = $response->filter('li')->extract(['_text']);

        static::assertResponseIsSuccessful();
        static::assertSame($expectedList, $actualList);
    }

    /**
     * @see config/packages/twig.yaml
     *
     * For reference (do not edit):
     * @see templates/topic5/template11.html.twig
     */
    #[Test]
    public function globalValueCanBeSetViaConfig(): void
    {
        $response = $this->client->request('GET', '/topic5/global-variables');

        static::assertResponseIsSuccessful();
        static::assertStringContainsString('Static global variable: global-variable-example.', $response->text());
    }

    /**
     * @see src/TwigExtension/TwigExtension.php
     *
     * For reference (do not edit):
     * @see templates/topic5/template11.html.twig
     */
    #[Test]
    public function globalValueCanBeSetViaTwigExtension(): void
    {
        TwigExtension::$dynamicGlobalValue = microtime();

        $response = $this->client->request('GET', '/topic5/global-variables');

        static::assertResponseIsSuccessful();
        static::assertStringContainsString('Dynamic global variable: ' . TwigExtension::$dynamicGlobalValue, $response->text());
    }

    /**
     * @see src/TwigExtension/TwigExtension.php
     * @see src/Service/Utilities/StringManipulator.php
     *
     * For reference (do not edit):
     * @see templates/topic5/template12.html.twig
     */
    #[Test]
    #[DataProvider('twigFilterCanBeDefinedProvider')]
    public function twigFilterCanBeDefined(string $valueAfterApplyingFilter): void
    {
        $response = $this->client->request('GET', '/topic5/twig-filter');

        static::assertResponseIsSuccessful();
        static::assertStringContainsString($valueAfterApplyingFilter, $response->text());
    }

    /**
     * @see templates/topic5/template13.html.twig
     */
    #[Test]
    public function stringsCanBeInterpolated(): void
    {
        $response = $this->client->request('GET', '/topic5/interpolation', ['a' => 10, 'b' => 50]);

        static::assertResponseIsSuccessful();
        static::assertStringContainsString('The sum of a and b is 60.', $response->text());
    }

    /**
     * @see config/packages/framework.yaml
     */
    #[Test]
    public function assetUrlCanBeDynamicallyComposed(): void
    {
        $response = $this->client->request('GET', '/topic5/asset');

        static::assertResponseIsSuccessful();
        static::assertStringContainsString(
            'https://www.hometogo.com/assets/logo/logo-color.svg',
            $response->filter('img')->attr('src')
        );
    }

    public static function twigFilterCanBeDefinedProvider(): iterable
    {
        yield ['aaaaa String value'];
        yield ['String value bb'];
        yield ['UTF-8'];
    }

    public static function escapingStrategyCanBeChangedProvider(): iterable
    {
        // Change templates/topic5/template3.html.twig to make the test pass.
        yield ['/topic5/escaping-strategy-change1'];
        // Change templates/topic5/template4.html.twig to make the test pass.
        yield ['/topic5/escaping-strategy-change2'];
        // Change \App\Controller\TemplatingWithTwig\TemplatingWithTwigController::template5 to make the test pass.
        yield ['/topic5/escaping-strategy-change3'];
    }

    private function getTemplateTokens(string $templatePath): array
    {
        $source = new Source(
            file_get_contents($templatePath),
            array_reverse(explode('/', $templatePath))[0],
            'UTF-8'
        );

        $tokens = [];

        try {
            $tokenStream = $this->twig->tokenize($source);

            while ($token = $tokenStream->next()) {
                $tokens[] = $token;
            }

            return $tokens;
        } catch (SyntaxError) {
            return $tokens;
        }
    }
}
