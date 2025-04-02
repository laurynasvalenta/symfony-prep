<?php

declare(strict_types=1);

namespace App\Tests\Topic4;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/*
 * This is a demonstration test for Symfony Certification Topic 4 (Routing).
 */
class Topic4Test extends WebTestCase
{
    #[Test]
    /**
     * Make this test pass by editing the config/routes.yaml file.
     */
    public function yamlConfigurationFileIsImported(): void
    {
        $client = static::createClient();

        $client->request('GET', '/custom-prefix/route1');

        static::assertSame('route1', $client->getResponse()->getContent());
    }

    #[Test]
    /**
     * Make this test pass by editing the src/Controller/Routing/RoutingController.php file.
     */
    public function routePriorityCanBeSetInRouteAttribute(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic4/priority/static');

        static::assertSame('With static text', $client->getResponse()->getContent());
    }

    #[Test]
    #[DataProvider('validParamUrlProvider')]
    public function routeParamCanBeRestrictedToCertainSymbols(string $path): void
    {
        $client = static::createClient();

        $client->request('GET', $path);

        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    #[Test]
    #[DataProvider('invalidParamUrlProvider')]
    public function routeIsNotFoundIfParamRestrictionsAreNotMatched(string $path): void
    {
        $client = static::createClient();

        $client->request('GET', $path);

        static::assertSame(404, $client->getResponse()->getStatusCode());
    }

    #[Test]
    #[DataProvider('defaultParamValueIsUsedIfParamIsNotProvidedProvider')]
    public function defaultParamValueIsUsedIfParamIsNotProvided(string $path, string $expectedParamValue): void
    {
        $client = static::createClient();

        $client->request('GET', $path);

        static::assertResponseIsSuccessful();
        static::assertStringContainsString($expectedParamValue, $client->getResponse()->getContent());
    }

    #[Test]
    public function urlsCanBeGeneratedFromRouteName(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic4/generated-link');
        $client->clickLink('Link');

        static::assertSame('/topic4/link-target', $client->getRequest()->getPathInfo());
    }

    #[Test]
    public function redirectControllerCanBeUsed(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic4/redirect');

        static::assertResponseRedirects('http://localhost/custom-prefix/route1', 307);
    }

    #[Test]
    public function internalRouteAttributesCanBeUsed(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic4/internal-route-attributes/es/json');

        static::assertResponseIsSuccessful();
        static::assertResponseHeaderSame('Content-Type', 'application/json');
        static::assertResponseHeaderSame('Content-Language', 'es');
    }

    #[Test]
    public function mismatchingHostResultsInNotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic4/match-by-host', [], [], ['HTTP_HOST' => 'unknown.com']);

        static::assertSame(404, $client->getResponse()->getStatusCode());
    }

    #[Test]
    public function expectedHostResultsInSuccess(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic4/match-by-host', [], [], ['HTTP_HOST' => 'example.com']);

        static::assertResponseIsSuccessful();
    }

    #[Test]
    /**
     * Make this test pass by editing the src/Service/Loader/RoutesLoader.php file.
     */
    public function routesCanBeLoadedViaCustomRouteLoader(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic4/custom-loaded-route');

        static::assertSame('Custom loaded route', $client->getResponse()->getContent());
    }

    #[Test]
    #[DataProvider('itIsPossibleToCheckIfARouteExistsProvider')]
    public function itIsPossibleToCheckIfARouteExists(string $routeNameToCheck, string $expectedResponseContent): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic4/route-name-checker/' . $routeNameToCheck);

        static::assertResponseIsSuccessful();
        static::assertStringContainsString($expectedResponseContent, $client->getResponse()->getContent());
    }

    public static function itIsPossibleToCheckIfARouteExistsProvider(): iterable
    {
        yield ['topic4-link-target', 'Route exists'];
        yield ['unknown-route', 'Route does not exist'];
    }

    public static function defaultParamValueIsUsedIfParamIsNotProvidedProvider(): iterable
    {
        yield ['/topic4/default-param1', 'default1'];
        yield ['/topic4/default-param2', 'default2'];
        yield ['/topic4/default-param3', 'default3'];
        yield ['/topic4/default-param1/custom-value1', 'custom-value1'];
        yield ['/topic4/default-param2/custom-value2', 'custom-value2'];
        yield ['/topic4/default-param3/custom-value3', 'custom-value3'];
    }

    /**
     * @return iterable<string[]>
     */
    public static function validParamUrlProvider(): iterable
    {
        yield ['/topic4/with-param1/0000123'];
        yield ['/topic4/with-param2/0000123'];
    }

    /**
     * @return iterable<string[]>
     */
    public static function invalidParamUrlProvider(): iterable
    {
        yield ['/topic4/with-param1/0000123a'];
        yield ['/topic4/with-param2/0000123a'];
    }
}