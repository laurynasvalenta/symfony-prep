<?php

declare(strict_types=1);

namespace App\Tests\Topic13;

use App\Model\Product;
use App\Service\Miscellaneous\ExtendedExpressionLanguage;
use App\Service\Miscellaneous\FileLocatorService;
use App\Service\Miscellaneous\DirectoryCloner;
use App\Service\Miscellaneous\ProductManager;
use App\Service\Miscellaneous\LockService;
use App\MessageHandler\Miscellaneous\DemoMessageHandler;
use App\Message\Miscellaneous\DemoMessage;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\Store\FlockStore;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\Miscellaneous\TranslationService;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Test suite for Symfony Certification Topic 13 (Miscellaneous).
 */
class Topic13Test extends WebTestCase
{
    const string SYMFONY_LOCK_FILE_NAME = '/tmp/symfony-locks//sf.test_resource.6H8883i.lock';
    const string LOCK_TEST_RESOURCE_NAME = 'test_resource';
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

        $this->prepareFilesystem();
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

    /**
     * Tests that templated emails can be sent using the Mailer component
     * and that the sent emails can be inspected in tests.
     *
     * @see src/Service/Miscellaneous/EmailNotifier.php
     * @see templates/topic13/welcome_email.html.twig
     * @see config/packages/mailer.yaml
     */
    #[Test]
    public function templatedEmailCanBeSentUsingMailerComponent(): void
    {
        $this->client->enableProfiler();
        $this->client->request('POST', '/topic13/send-welcome-email');

        static::assertResponseIsSuccessful();
        static::assertStringContainsString('Welcome email sent successfully!', $this->client->getResponse()->getContent());

        $email = $this->client->getProfile()->getCollector('mailer')->getEvents()->getMessages()[0];
        $htmlContent = $email->getHtmlBody();

        static::assertEquals('Welcome to our platform!', $email->getSubject());
        static::assertEquals('test@example.com', $email->getTo()[0]->getAddress());
        static::assertEquals('noreply@example.com', $email->getFrom()[0]->getAddress());

        static::assertStringContainsString('Welcome, John Doe!', $htmlContent);
        static::assertStringContainsString('Thank you for joining our platform.', $htmlContent);
    }

    /**
     * Tests that files can be located using multiple filtering parameters
     * with the Symfony Finder component.
     *
     * @see src/Service/Miscellaneous/FileLocatorService.php
     */
    #[Test]
    public function fileCanBeLocatedBasedOnMultipleParams(): void
    {
        $fileLocator = static::getContainer()->get(FileLocatorService::class);
        $results = $fileLocator->findFilesWithMultipleFilters('/tmp/symfony_test_files');

        static::assertCount(2, $results);
        static::assertContains('file1.txt', $results);
        static::assertContains('file3.txt', $results);
        static::assertNotContains('file2.txt', $results);
        static::assertNotContains('data.json', $results);
    }

    /**
     * Tests that a whole directory can be copied using the Symfony Filesystem component.
     *
     * @see src/Service/Miscellaneous/DirectoryCloner.php
     */
    #[Test]
    public function wholeDirectoryCanBeCopiedByUsingFilesystemComponent(): void
    {
        $sourceDir = '/tmp/symfony_source_dir';
        $targetDir = '/tmp/symfony_target_dir';

        $directoryCloner = static::getContainer()->get(DirectoryCloner::class);
        $directoryCloner->cloneDirectory($sourceDir, $targetDir);
        
        static::assertDirectoryExists($targetDir);
        static::assertFileExists($targetDir . '/file1.txt');
        static::assertEquals(
            file_get_contents($sourceDir . '/file1.txt'),
            file_get_contents($targetDir . '/file1.txt')
        );
    }

    /**
     * Tests that read locks can be acquired by multiple readers simultaneously.
     *
     * @see src/Service/Miscellaneous/LockService.php
     */
    #[Test]
    public function readLockCanBeAcquiredByMultipleReaders(): void
    {
        /** @var LockService $lockService */
        $lockService = static::getContainer()->get(LockService::class);

        $key = new Key(self::LOCK_TEST_RESOURCE_NAME);

        $result1 = $lockService->acquireReadLock($key);

        $resource = $key->getState(FlockStore::class)[1];
        $key->removeState(FlockStore::class);

        $result2 = $lockService->acquireReadLock($key);

        static::assertIsResource($resource);
        static::assertTrue($result1);
        static::assertTrue($result2);
        static::assertFileExists(self::SYMFONY_LOCK_FILE_NAME);
    }

    #[Test]
    public function readLockCannotBeAcquiredIfWriteLockIsAlreadyAcquired(): void
    {
        /** @var LockService $lockService */
        $lockService = static::getContainer()->get(LockService::class);

        $key = new Key(self::LOCK_TEST_RESOURCE_NAME);

        $result1 = $lockService->acquireWriteLock($key);

        $resource = $key->getState(FlockStore::class)[1];
        $key->removeState(FlockStore::class);

        $result2 = $lockService->acquireReadLock($key);

        static::assertIsResource($resource);
        static::assertTrue($result1);
        static::assertFalse($result2);
        static::assertFileExists(self::SYMFONY_LOCK_FILE_NAME);
    }

    /**
     * Tests that ICU select functionality can be used for gender-based translations.
     * Demonstrates how to handle different gender selections using data providers.
     *
     * @see src/Service/Miscellaneous/TranslationService.php
     */
    #[Test]
    #[DataProvider('icuSelectDataProvider')]
    public function basicSwitchLogicCanBeUsedWhenTranslating(string $gender, string $name, string $expected): void
    {
        /** @var TranslationService $translationService */
        $translationService = static::getContainer()->get(TranslationService::class);

        $result = $translationService->translateWithIcuSelect($gender, $name, 'en');

        static::assertEquals($expected, $result);
    }

    /**
     * Tests that ICU plural functionality can be used for count-based translations.
     * Demonstrates how to handle different plural forms using data providers.
     *
     * @see src/Service/Miscellaneous/TranslationService.php
     */
    #[Test]
    #[DataProvider('icuPluralDataProvider')]
    public function icuPluralFunctionalityCanBeUsedForCountBasedTranslations(int $count, string $expected): void
    {
        /** @var TranslationService $translationService */
        $translationService = static::getContainer()->get(TranslationService::class);

        $result = $translationService->translateWithIcuPlural($count, 'en');

        static::assertEquals($expected, $result);
    }

    public static function icuSelectDataProvider(): array
    {
        return [
            'male' => ['male', 'John', 'Hello, Mr. John!'],
            'female' => ['female', 'Marie', 'Hello, Ms. Marie!'],
            'other' => ['other', 'Alex', 'Hello, Alex!'],
            'unknown' => ['unknown', 'Taylor', 'Hello, Taylor!'], // fallback to 'other'
        ];
    }

    public static function icuPluralDataProvider(): array
    {
        return [
            'zero' => [0, 'No items found.'],
            'one' => [1, '1 item found.'],
            'many' => [5, '5 items found.'],
            'custom' => [2, '2 items found.'],
        ];
    }

    private function prepareFilesystem(): void
    {
        foreach (['/tmp/symfony_test_files', '/tmp/symfony_target_dir', '/tmp/symfony_source_dir', '/tmp/symfony-locks'] as $dir) {
            if (is_dir($dir)) {
                array_map('unlink', glob($dir . '/*'));
                rmdir($dir);
            }
        }

        mkdir('/tmp/symfony-locks', 0755, true);

        foreach (['/tmp/symfony_test_files', '/tmp/symfony_source_dir'] as $dir) {
            mkdir($dir, 0755, true);

            file_put_contents($dir . '/file1.txt', 'This is an important file with some content.');
            file_put_contents($dir . '/file2.txt', 'Small file.');
            file_put_contents($dir . '/file3.txt', 'This is another file with more content than the second one.');
            file_put_contents($dir . '/data.json', '{"key": "value", "number": 42}');
        }
    }
}
