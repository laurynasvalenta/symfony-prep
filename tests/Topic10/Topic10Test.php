<?php

declare(strict_types=1);

namespace App\Tests\Topic10;

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/*
 * This is a demonstration test for Symfony Certification Topic 10 (HTTP Caching).
 */
class Topic10Test extends WebTestCase
{
    private KernelBrowser $client;
    private HttpClientInterface $cachingClient;

    public function setUp(): void
    {
        $this->deleteServerSideCache();
        $this->deleteClientSideCache();

        parent::setUp();

        $this->client = static::createClient();

        $this->cachingClient = new CachingHttpClient(
            HttpClient::create(),
            new Store(__DIR__ . '/../../var/cache/test/http_cache_client/')
        );

        static::ensureKernelShutdown();
    }

    /**
     * Make this test pass by adding correct cache controls in the controller.
     *
     * @see \App\Controller\HttpCaching\HttpCachingController::publicCacheableResponse()
     */
    #[Test]
    public function clientSideCacheIsAppliedForPublicCacheableResponses(): void
    {
        $response = $this->cachingClient->request('GET', 'http://nginx/topic10/public-cacheable-response');
        $this->deleteServerSideCache();
        $cachedResponse = $this->cachingClient->request('GET', 'http://nginx/topic10/public-cacheable-response');

        static::assertEquals($response->getContent(), $cachedResponse->getContent());
    }

    /**
     * This is a showcase test to demonstrate that responses to POST requests are not cached.
     */
    #[Test]
    public function clientSideCacheIsNotAppliedForNonSafeResponses(): void
    {
        $response = $this->cachingClient->request('POST', 'http://nginx/topic10/public-cacheable-response');
        $this->deleteServerSideCache();
        $cachedResponse = $this->cachingClient->request('POST', 'http://nginx/topic10/public-cacheable-response');

        static::assertNotEquals($response->getContent(), $cachedResponse->getContent());
    }

    /**
     * Make this test pass by setting correct cache information to the response.
     *
     * @see \App\Controller\HttpCaching\HttpCachingController::noStoreResponse()
     */
    #[Test]
    public function clientSideCacheIsNotAppliedForNoStoreResponses(): void
    {
        $response = $this->cachingClient->request('GET', 'http://nginx/topic10/no-store-response');
        $cachedResponse = $this->cachingClient->request('GET', 'http://nginx/topic10/no-store-response');

        static::assertNotEquals($response->getContent(), $cachedResponse->getContent());
    }

    /**
     * This is a showcase test to demonstrate that the client-side cache is not applied if the response is private.
     */
    #[Test]
    public function clientSideCacheIsAppliedForPrivateCacheableResponses(): void
    {
        $response = $this->cachingClient->request('GET', 'http://nginx/topic10/private-cacheable-response');
        $cachedResponse = $this->cachingClient->request('GET', 'http://nginx/topic10/private-cacheable-response');

        static::assertNotEquals($response->getContent(), $cachedResponse->getContent());
    }

    /**
     * Make this test pass by setting correct cache information to the response and enabling the reverse proxy.
     *
     * @see \App\Controller\HttpCaching\HttpCachingController::publicCacheableResponse()
     */
    #[Test]
    public function symfonyReverseProxyCachePubliclyCachedResponses(): void
    {
        $response = $this->client->request('GET', '/topic10/public-cacheable-response');
        $cachedResponse = $this->client->request('GET', '/topic10/public-cacheable-response');

        static::assertEquals($response->text(), $cachedResponse->text());
    }

    /**
     * Make this test pass by setting correct cache information to the response.
     *
     * @see \App\Controller\HttpCaching\HttpCachingController::privateCacheableResponse()
     */
    #[Test]
    public function symfonyReverseProxyCacheDoesNotCachePrivateResponses(): void
    {
        $response = $this->client->request('GET', '/topic10/private-cacheable-response');
        $cachedResponse = $this->client->request('GET', '/topic10/private-cacheable-response');

        static::assertNotEquals($response->text(), $cachedResponse->text());
    }

    /**
     * Make this test pass by setting correct cache information to the response.
     *
     * @see \App\Controller\HttpCaching\HttpCachingController::noStoreResponse()
     */
    #[Test]
    public function symfonyReverseProxyCacheDoesNotCacheNoStoreResponses(): void
    {
        $response = $this->client->request('GET', '/topic10/no-store-response');
        $cachedResponse = $this->client->request('GET', '/topic10/no-store-response');

        static::assertNotEquals($response->text(), $cachedResponse->text());
    }

    /**
     * Make this test pass by setting correct cache information to the response.
     *
     * @see \App\Controller\HttpCaching\HttpCachingController::validatableResponse()
     */
    #[Test]
    #[DataProvider('validationCacheIsHitProvider')]
    public function validationCacheIsHit(array $headers): void
    {
        $this->client->request('GET', '/topic10/validatable-response');
        $response = $this->client->getResponse();

        $this->deleteServerSideCache();

        $this->client->request('GET', '/topic10/validatable-response', [], [], $headers);
        $cachedResponse = $this->client->getResponse();

        static::assertEquals(200, $response->getStatusCode());
        static::assertNotEmpty($response->getContent());
        static::assertEquals(304, $cachedResponse->getStatusCode());
        static::assertEmpty($cachedResponse->getContent());
    }

    public static function validationCacheIsHitProvider(): iterable
    {
        yield 'If-None-Match' => [
            ['HTTP_IF_NONE_MATCH' => 'W/"unique-content-hash"'],
        ];

        yield 'If-Modified-Since' => [
            ['HTTP_IF_MODIFIED_SINCE' => (new DateTimeImmutable('-2 weeks'))->format(DateTimeInterface::RFC7231)],
        ];
    }

    #[Test]
    #[DataProvider('validationCacheIsMissedProvider')]
    public function validationCacheIsMissed(string $method, array $headers): void
    {
        $this->client->request($method, '/topic10/validatable-response');
        $response1 = $this->client->getResponse();

        $this->deleteServerSideCache();

        $this->client->request($method, '/topic10/validatable-response', [], [], $headers);
        $response2 = $this->client->getResponse();

        static::assertEquals(200, $response1->getStatusCode());
        static::assertNotEmpty($response1->getContent());
        static::assertEquals(200, $response2->getStatusCode());
        static::assertNotEmpty($response2->getContent());
    }

    public static function validationCacheIsMissedProvider(): iterable
    {
        yield 'Not cacheable method' => [
            'POST',
            ['HTTP_IF_NONE_MATCH' => 'W/"unique-content-hash"'],
        ];

        yield 'If-None-Match' => [
            'GET',
            ['HTTP_IF_NONE_MATCH' => 'W/"unique-content-hash-that-does-not-match"'],
        ];

        yield 'If-Modified-Since' => [
            'GET',
            ['HTTP_IF_MODIFIED_SINCE' => (new DateTimeImmutable('-4 weeks'))->format(DateTimeInterface::RFC7231)],
        ];
    }

    /**
     * Make this test pass by configuring the ESI functionality and including the ESI content in the template.
     *
     * ESI fragment should render the response of \App\Controller\HttpCaching\HttpCachingController::publicCacheableResponse() controller.
     *
     * @see config/packages/framework.yaml
     * @see templates/topic10/page-contains-esi-fragment.html.twig
     */
    #[Test]
    public function edgeSideIncludeTagIsGeneratedWhenEsiIsEnabled(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/topic10/page-contains-esi-fragment', [], [], [
            'HTTP_SURROGATE_CAPABILITY' => 'ESI/1.0',
        ]);
        $response = $this->client->getResponse();

        static::assertStringContainsString('<esi:include src="/_fragment?_hash=', $response->getContent());
    }

    #[Test]
    public function edgeSideIncludeTagIsReplacedWithActualContentWhenEsiIsDisabled(): void
    {
        $response1 = $this->client->request('GET', '/topic10/page-contains-esi-fragment');
        $response2 = $this->client->request('GET', '/topic10/page-contains-esi-fragment');

        $response = $this->client->getResponse();

        static::assertStringNotContainsString('<esi:include src="/_fragment?_hash=', $response->getContent());

        static::assertNotEquals(
            $response1->filter('.non-cached-content')->text(),
            $response2->filter('.non-cached-content')->text()
        );
        static::assertEquals(
            $response1->filter('.cached-content')->text(),
            $response2->filter('.cached-content')->text()
        );
        static::assertSelectorTextContains('.cached-content', 'This response is public and cacheable: ');
    }

    protected function deleteServerSideCache(): void
    {
        (new Filesystem())->remove(__DIR__ . '/../../var/cache/test/http_cache/');
        (new Filesystem())->remove(__DIR__ . '/../../var/cache/dev/http_cache/');
    }

    protected function deleteClientSideCache(): void
    {
        (new Filesystem())->remove(__DIR__ . '/../../var/cache/test/http_cache_client/');
        (new Filesystem())->remove(__DIR__ . '/../../var/cache/dev/http_cache_client/');
    }
}
