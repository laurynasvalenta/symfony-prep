<?php

declare(strict_types=1);

namespace App\Tests\Topic13;

use App\Model\Product;
use App\Service\Miscellaneous\ExtendedExpressionLanguage;
use App\Service\Miscellaneous\ProductManager;
use App\MessageHandler\Miscellaneous\DemoMessageHandler;
use App\Message\Miscellaneous\DemoMessage;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Test suite for Symfony Certification Topic 13 (Miscellaneous).
 */
class Topic13Test extends WebTestCase
{
    private ?KernelBrowser $client = null;
    private ?DemoMessageHandler $handler = null;
    private ?ProductManager $productManager = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient(['debug' => true]);
        $container = static::getContainer();

        $this->handler = $container->get(DemoMessageHandler::class);
        $this->productManager = static::getContainer()->get(ProductManager::class);
    }

    /**
     * Tests that the ExpressionLanguage component can be extended with a custom function.
     *
     * @see src/Service/Miscellaneous/ExtendedExpressionLanguage.php
     */
    #[Test]
    public function expressionLanguageComponentCanBeExtendedWithCustomFunctions(): void
    {
        $expressionLanguageService = static::getContainer()->get(ExtendedExpressionLanguage::class);

        $result = $expressionLanguageService->evaluate('random_url("example.com")');

        static::assertIsString($result);
        static::assertStringStartsWith('https://example.com/', $result);
        static::assertMatchesRegularExpression('/[a-f0-9]{32}$/', $result);
    }

    /**
     * This test ensures that a custom exception can be caught by an event
     * listener, which then issues a redirect, preventing the default
     * error page from being shown.
     *
     * @see src/Controller/Miscellaneous/CustomErrorController.php
     * @see src/EventListener/Topic13ExceptionListener.php
     */
    #[Test]
    public function errorControllerCanBeCustomizedToHandleExceptionsDifferently(): void
    {
        $this->client->request('GET', '/topic13/error');

        static::assertResponseRedirects('/');
    }

    /**
     * Tests that Twig's dump() function renders its output directly into the
     * response when debug mode is enabled for the test client.
     *
     * @see templates/miscellaneous/dump.html.twig
     * @see config/packages/dev/twig.yaml
     */
    #[Test]
    public function twigDumpFunctionCanBeUsedForObjectDebugging(): void
    {
        $this->client->request('GET', '/topic13/twig-dump');

        static::assertResponseIsSuccessful();

        $content = $this->client->getResponse()->getContent();

        static::assertStringContainsString('App\Model\DemoUser', $content);
        static::assertStringContainsString('name', $content);
        static::assertStringContainsString('John Doe', $content);
        static::assertStringContainsString('email', $content);
        static::assertStringContainsString('john@example.com', $content);
    }

    /**
     * Tests the PSR-6 cache functionality for storing and retrieving a value.
     *
     * @see src/Controller/Miscellaneous/CacheController.php
     */
    #[Test]
    public function psr6CacheInterfaceProvidesCachingFunctionality(): void
    {
        $crawler = $this->client->request('GET', '/topic13/psr6-cache');
        $firstResponse = $this->client->getResponse()->getContent();

        $this->client->request('GET', '/topic13/psr6-cache');
        $secondResponse = $this->client->getResponse()->getContent();

        static::assertResponseIsSuccessful();
        static::assertStringContainsString('Result:', $crawler->text());
        static::assertEquals($firstResponse, $secondResponse);
    }

    /**
     * Tests the Cache Contracts for a simpler caching API.
     *
     * @see src/Controller/Miscellaneous/CacheController.php
     */
    #[Test]
    public function cacheContractsInterfaceProvidesAlternativeCachingApi(): void
    {
        $crawler = $this->client->request('GET', '/topic13/cache-contracts');
        $firstResponse = $this->client->getResponse()->getContent();

        $this->client->request('GET', '/topic13/cache-contracts');
        $secondResponse = $this->client->getResponse()->getContent();

        static::assertResponseIsSuccessful();
        static::assertStringContainsString('Result:', $crawler->text());
        static::assertEquals($firstResponse, $secondResponse);
    }

    /**
     * Tests that cache stampedes can be mitigated using early expiration.
     *
     * @see src/Controller/Miscellaneous/CacheController.php
     */
    #[Test]
    public function cacheStampedeCanBePreventedUsingEarlyExpiration(): void
    {
        $crawler = $this->client->request('GET', '/topic13/cache-early-expiration');
        $firstResponse = $this->client->getResponse()->getContent();

        $this->client->request('GET', '/topic13/cache-early-expiration');
        $secondResponse = $this->client->getResponse()->getContent();

        static::assertResponseIsSuccessful();
        static::assertStringContainsString('Result:', $crawler->text());
        static::assertNotEquals($firstResponse, $secondResponse);
    }

    /**
     * Tests that the Process component can execute a command programmatically.
     *
     * @see src/Controller/Miscellaneous/CacheController.php
     */
    #[Test]
    public function processComponentCanExecuteConsoleCommandsProgrammatically(): void
    {
        $this->client->request('GET', '/topic13/process-demo');

        $content = $this->client->getResponse()->getContent();

        static::assertResponseIsSuccessful();
        static::assertStringContainsString('$ecretf0rt3st', $content);
    }

    /**
     * This test demonstrates how serialization groups can be used to control the output format.
     * The `SerializedName` attribute is also demonstrated, showing how a property can be renamed.
     *
     * @see src/Service/Miscellaneous/ProductManager.php
     */
    #[Test]
    public function serializerGroupsCanControlSerializationOutput(): void
    {
        $product = new Product(1, 'MBP-14', 'MacBook Pro 14', 1999);

        $json = $this->productManager->convertProductToJson($product);
        $data = json_decode($json, true);

        static::assertArrayNotHasKey('id', $data);
        static::assertArrayHasKey('cost', $data);
        static::assertEquals(1999, $data['cost']);
        static::assertArrayNotHasKey('price', $data);
    }

    /**
     * This test demonstrates how the serializer can populate an existing object
     * instead of creating a new one, which is useful for updating entities.
     *
     * @see src/Service/Miscellaneous/ProductManager.php
     */
    #[Test]
    public function serializerCanPopulateExistingObjectsDuringDeserialization(): void
    {
        $originalProduct = new Product(1, 'MBP-14', 'MacBook Pro 14', 1999);
        $updateJson = '{"name": "MacBook Pro 14 (M3)", "id": 300}';

        $updatedProduct = $this->productManager->updateProductFromJson($originalProduct, $updateJson);

        static::assertSame($originalProduct, $updatedProduct);
        static::assertEquals('MacBook Pro 14 (M3)', $updatedProduct->name);
        static::assertEquals(1, $updatedProduct->id);
        static::assertEquals(1999, $updatedProduct->price);
    }

    /**
     * Tests that a custom Messenger transport can be implemented and used.
     *
     * @see src/Transport/Miscellaneous/CustomTransportFactory.php
     * @see config/services.yaml
     */
    #[Test]
    public function customTransportCanBeImplementedForSymfonyMessengerComponent(): void
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->getContainer()->get(MessageBusInterface::class);
        $messageBus->dispatch(new DemoMessage(42));

        $processedMessages = $this->handler->getProcessedMessages();

        static::assertCount(1, $processedMessages);
        static::assertGreaterThanOrEqual(42, $processedMessages[0]->value);
    }

    /**
     * Tests that custom Messenger middleware can modify a message in the bus.
     *
     * @see src/Middleware/Miscellaneous/IncrementMiddleware.php
     * @see config/packages/messenger.yaml
     * @see config/services.yaml
     */
    #[Test]
    public function customMiddlewareCanIncrementIntegerValuesInMessages(): void
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->getContainer()->get(MessageBusInterface::class);
        $messageBus->dispatch(new DemoMessage(42));

        $processedMessages = $this->handler->getProcessedMessages();

        static::assertCount(1, $processedMessages);
        static::assertGreaterThanOrEqual(43, $processedMessages[0]->value);
    }
}
