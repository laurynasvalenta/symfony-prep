<?php

declare(strict_types=1);

namespace App\Tests\Topic3;

use App\Model\ComplexObject;
use App\ValueResolver\TestResolver;
use DateTime;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\InMemoryUser;

/*
 * This is a demonstration test for Symfony Certification Topic 3 (Controller).
 *
 * Please DO NOT edit this file and make the tests pass by editing the
 * src/Controller/ExamplesFromAbstractController.php file.
 */
class Topic03Test extends WebTestCase
{
    private const string USER_IDENTIFIER = 'test';

    /**
     * Make the test pass by editing the src/Controller/ExamplesFromAbstractController.php file.
     */
    #[Test]
    public function routerCanBeAccessedFromContainerInAbstractController(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/router');

        static::assertSame('http://localhost/topic3/router', $client->getResponse()->getContent());
    }

    #[Test]
    public function routerCanBeAccessedViaHelperInAbstractController(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/router2');

        static::assertSame('http://localhost/topic3/router2', $client->getResponse()->getContent());
    }

    #[Test]
    public function requestStackCanBeAccessedFromContainerInAbstractController(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/main-request-checker');

        static::assertSame('main request', $client->getResponse()->getContent());
    }

    #[Test]
    public function subRequestIsCorrectlyIdentified(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/forward-to-main-request-checker');

        static::assertSame('sub-request', $client->getResponse()->getContent());
    }

    #[Test]
    public function httpKernelCanBeAccessedFromContainerInAbstractController(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/http-kernel');

        static::assertSame('sub-request', $client->getResponse()->getContent());
    }

    #[Test]
    public function serializerCanBeAccessedFromContainerInAbstractController(): void
    {
        TestResolver::$complexObject = $complexObject = new ComplexObject(
            microtime(),
            new DateTime('2021-01-01')
        );

        $client = static::createClient();

        $client->request('GET', '/topic3/serializer');

        static::assertSame(
            json_encode(['name' => $complexObject->getName(), 'date' => $complexObject->getDate()->format('c')]),
            $client->getResponse()->getContent()
        );
    }

    #[Test]
    public function loggedInUserIsGrantedAccess(): void
    {
        $client = static::createClient();

        $client->loginUser(new InMemoryUser('test', 'test', ['ROLE_USER']));
        $client->request('GET', '/topic3/authorization');

        static::assertSame('granted', $client->getResponse()->getContent());
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    #[Test]
    public function anonymousInUserIsDeniedAccess(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/authorization');

        static::assertSame('denied', $client->getResponse()->getContent());
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    #[Test]
    public function anonymousUserReceives403(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/protected');

        static::assertSame(401, $client->getResponse()->getStatusCode());
    }

    #[Test]
    public function authenticatedUserReceives200(): void
    {
        $client = static::createClient();

        $client->loginUser(new InMemoryUser('test', 'test', ['ROLE_USER']));
        $client->request('GET', '/topic3/protected');

        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    #[Test]
    public function twigCanBeAccessedFromContainerInAbstractController(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/twig1');

        static::assertSame('This is a template for topic 3.', $client->getResponse()->getContent());
    }

    #[Test]
    public function twigHelperMethodsCanBeUsed(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/twig2');

        static::assertSame('This is another template for topic 3.', $client->getResponse()->getContent());
    }

    #[Test]
    public function formFactoryCanBeAccessedFromContainerInAbstractController(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/form');

        static::assertCount(1, $client->getCrawler()->filter('form'));
    }

    #[Test]
    public function relevantStatusCodeIsSetForInvalidForms(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/form');

        $client->submitForm('Submit', ['simple_form[name]' => '']);

        static::assertSame(422, $client->getResponse()->getStatusCode());
    }

    #[Test]
    public function securityTokenStorageCanBeAccessedFromContainerInAbstractController(): void
    {
        $client = static::createClient();

        $client->loginUser(new InMemoryUser(self::USER_IDENTIFIER, 'test', ['ROLE_USER']));
        $client->request('GET', '/topic3/token-storage');

        static::assertSame(self::USER_IDENTIFIER, $client->getResponse()->getContent());
    }

    #[Test]
    public function anonymousUserIsWelcomed(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/token-storage');

        static::assertSame('Welcome, Anonymous!', $client->getResponse()->getContent());
    }

    #[Test]
    public function csrfTokenIsIssuedAndValidatedWithTheAbstractController(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/csrf-token');
        $client->request('GET', '/topic3/csrf-token', ['token' => $client->getResponse()->getContent()]);

        static::assertSame('Valid token provided!', $client->getResponse()->getContent());
    }

    #[Test]
    public function containerParametersCanBeAccessedFromContainerInAbstractController(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/container-parameters');

        static::assertSame(static::getContainer()->getParameter('container.build_id'), $client->getResponse()->getContent());
    }

    #[Test]
    public function fileIsUploadedSuccessfully(): void
    {
        @unlink(__DIR__ . '/../../var/uploads/symfony.svg');

        $client = static::createClient();

        $client->request('GET', '/topic3/upload-file');
        $client->submitForm('Submit', ['file_upload_form[file]' => __DIR__ . '/symfony.svg']);

        static::assertFileEquals(__DIR__ . '/symfony.svg', __DIR__ . '/../../var/uploads/symfony.svg');
    }

    #[Test]
    public function fileIsDownloadedAndDeleted(): void
    {
        @unlink(__DIR__ . '/../../var/uploads/symfony.svg');

        $client = static::createClient();

        $client->request('GET', '/topic3/upload-file');
        $client->submitForm('Submit', ['file_upload_form[file]' => __DIR__ . '/symfony.svg']);

        $client->request('GET', '/topic3/download-file');

        static::assertEquals(file_get_contents(__DIR__ . '/symfony.svg'), $client->getInternalResponse()->getContent());
        static::assertFileDoesNotExist(__DIR__ . '/../../var/uploads/symfony.svg');
    }

    /**
     * Make the test pass by editing the src/ValueResolver/CustomResolver.php file.
     */
    #[Test]
    public function customValueResolverCalculatesSum(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic3/custom-resolver', ['a' => 13, 'b' => 29]);

        static::assertSame('abSum is 42.', $client->getResponse()->getContent());
    }
}
