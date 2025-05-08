<?php

declare(strict_types=1);

namespace App\Tests\Topic9;

use App\Controller\Security\FirewalledPages;
use App\Model\User;
use App\Service\PasswordHasher\ExtraSafePasswordHasherFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\User\InMemoryUser;

/*
 * This is a demonstration test for Symfony Certification Topic 9 (Security).
 */
class Topic9Test extends WebTestCase
{
    private KernelBrowser $client;
    private Security $security;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->security = static::getContainer()->get(Security::class);
    }

    /**
     * Make this test pass by introducing a new firewall in the security bundle config.
     *
     * @see config/packages/security.yaml
     */
    #[Test]
    public function blueFirewallIsEnabledBasedOnUrl(): void
    {
        $this->client->request('GET', '/topic9/blue-firewall/public-page');

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('*', 'Page under blue firewall.');
    }

    /**
     * Make this test pass by modifying the protected-page controller.
     *
     * @see src/Controller/Security/FirewalledPages.php
     */
    #[Test]
    public function unauthenticatedCannotAccessProtectedPage(): void
    {
        $this->client->request('GET', '/topic9/blue-firewall/protected-page');

        static::assertResponseStatusCodeSame(401);
    }

    #[Test]
    public function authenticatedUserOnBlueFirewallCanAccessProtectedPage(): void
    {
        $this->client->loginUser(new InMemoryUser('test', 'test', ['ROLE_USER']), 'blue');
        $this->client->request('GET', '/topic9/blue-firewall/protected-page');

        static::assertResponseIsSuccessful();
    }

    /**
     * Make this test pass by introducing a new firewall in the security bundle config.
     *
     * @see config/packages/security.yaml
     */
    #[Test]
    public function greenFirewallIsEnabledBasedOnUrl(): void
    {
        $this->client->request('GET', '/topic9/green-firewall/public-page');

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('*', 'Page under green firewall.');
    }

    /**
     * Make this test pass by adjusting the configuration of the green firewall.
     *
     * @see config/packages/security.yaml
     */
    #[Test]
    public function authenticationOnBlueFirewallIsValidOnGreenFirewall(): void
    {
        $this->client->loginUser(new InMemoryUser('test', 'test', ['ROLE_USER']), 'blue');
        $this->client->request('GET', '/topic9/green-firewall/protected-page');

        static::assertResponseIsSuccessful();
    }

    /**
     * Make this test pass by implementing the following logic in the ExtraSafePasswordHasher class:
     *  - hash the password using the Pbkdf2PasswordHasher
     *  - reverse the hashed password
     *  - configure the SecurityBundle to use this *better* password hasher
     *
     * @see src/Service/PasswordHasher/ExtraSafePasswordHasher.php
     * @see src/Service/PasswordHasher/ExtraSafePasswordHasherFactory.php
     * @see config/packages/security.yaml
     */
    #[Test]
    public function customPasswordHasherCanBeImplemented(): void
    {
        $user = new User('test');

        $hashedPassword = static::getContainer()->get(UserPasswordHasherInterface::class)
            ->hashPassword($user, 'password');

        self::assertSame(
            '==wXKXGNi7sBkzjRVLEhe8fCjzrDiBEIZMTuEajq7O9aiuFbQlquU63A',
            $hashedPassword
        );
    }

    /**
     * Make this test pass by modifying the role-protected-page controller.
     *
     * @see src/Controller/Security/FirewalledPages.php
     */
    #[Test]
    #[DataProvider('adminCanAccessPageRestrictedToAnotherRoleProvider')]
    public function adminCanAccessPageRestrictedToAnotherRole(string $name, array $roles): void
    {
        $this->client->loginUser(new InMemoryUser($name, 'test', $roles), 'blue');
        $this->client->request('GET', '/topic9/blue-firewall/role-protected-page');

        static::assertResponseIsSuccessful();
    }

    public static function adminCanAccessPageRestrictedToAnotherRoleProvider(): iterable
    {
        yield ['test_user1', ['ROLE_USER','ROLE_REGIONAL_ADMIN']];
        yield ['test_user2', ['ROLE_USER','ROLE_SUPER_ADMIN']];
    }

    /**
     * Make this test pass by modifying the special-roles-summary controller.
     * make sure to configure the switch user functionality in the security bundle config.
     *
     * @see src/Controller/Security/FirewalledPages.php
     * @see config/packages/security.yaml
     */
    #[Test]
    public function specialRoleForImpersonationExists(): void
    {
        $this->client->loginUser(new InMemoryUser(
            'test_user3',
            'test',
            ['ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH']
        ), 'blue');

        $this->client->request('GET', '/topic9/blue-firewall/special-roles-summary?_switch_user=test_user1');

        $this->client->followRedirect();

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('*', 'Is the user currently impersonating another user: Yes');
        static::assertSelectorTextContains('*', 'Is the user authenticated via the remember me token: No');
    }

    /**
     * Make this test pass by modifying the special-roles-summary controller.
     * Make sure to configure the remember me functionality in the security bundle config.
     *
     * @see src/Controller/Security/FirewalledPages.php
     * @see config/packages/security.yaml
     */
    #[Test]
    public function specialRoleForRememberedUsersExists(): void
    {
        $this->client->request('GET', '/topic9/blue-firewall/login');

        $this->client->submitForm('Login', [
            '_username' => 'test_user3',
            '_password' => 'test',
            '_remember_me' => true,
        ]);

        foreach ($this->client->getCookieJar()->all() as $cookie) {
            if ($cookie->getName() !== 'REMEMBERME') {
                $this->client->getCookieJar()->expire($cookie->getName(), $cookie->getPath(), $cookie->getDomain());
                $this->client->getCookieJar()->flushExpiredCookies();
            }
        }

        $this->client->request('GET', '/topic9/blue-firewall/special-roles-summary');

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('*', 'Is the user currently impersonating another user: No');
        static::assertSelectorTextContains('*', 'Is the user authenticated via the remember me token: Yes');
        static::assertSelectorTextContains('*', 'Is completely authenticated: No');
    }

    /**
     * Make this test pass by modifying the special-roles-summary controller.
     *
     * @see src/Controller/Security/FirewalledPages.php
     */
    #[Test]
    public function completeAuthenticationHasDedicatedSpecialRole(): void
    {
        $this->client->request('GET', '/topic9/blue-firewall/login');

        $this->client->submitForm('Login', [
            '_username' => 'test_user3',
            '_password' => 'test',
            '_remember_me' => true,
        ]);

        $this->client->request('GET', '/topic9/blue-firewall/special-roles-summary');

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('*', 'Is the user currently impersonating another user: No');
        static::assertSelectorTextContains('*', 'Is the user authenticated via the remember me token: No');
        static::assertSelectorTextContains('*', 'Is completely authenticated: Yes');
    }

    /**
     * Make this test pass by configuring access control rule in the security bundle config.
     *
     * @see config/packages/security.yaml
     */
    #[Test]
    public function accessRestrictedPageCannotBeAccessedWithoutProperRole(): void
    {
        $this->client->loginUser(new InMemoryUser('test', 'test', ['ROLE_USER']), 'blue');

        $this->client->request('GET', '/topic9/blue-firewall/restricted/super-admin');

        static::assertResponseStatusCodeSame(403);
    }

    #[Test]
    public function accessRestrictedPageCanBeAccessedWithProperRole(): void
    {
        $this->client->loginUser(
            new InMemoryUser('test_user2', 'test', ['ROLE_USER','ROLE_SUPER_ADMIN']),
            'blue'
        );

        $this->client->request('GET', '/topic9/blue-firewall/restricted/super-admin');

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('*', 'Page restricted for super-admin');
    }

    /**
     * Make this test pass by adjusting the security bundle config, access control rules.
     *
     * @see config/packages/security.yaml
     */
    #[Test]
    public function accessControlRulesAreEvaluatedSequentially(): void
    {
        $this->client->loginUser(
            new InMemoryUser('test_user1', 'test', ['ROLE_USER','ROLE_REGIONAL_ADMIN']),
            'blue'
        );

        $this->client->request('GET', '/topic9/blue-firewall/restricted/regional-admin');

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('*', 'Page restricted for regional-admin');
    }

    /**
     * Make this test pass by implementing the custom voter.
     *
     * @see src/Service/Voter/CustomVoter.php
     */
    #[Test]
    public function customVoterApprovesAccessToNonGmail(): void
    {
        $this->client->loginUser(
            new InMemoryUser('test_user1@yahoo.com', 'test', ['ROLE_USER','ROLE_REGIONAL_ADMIN']),
            'blue'
        );

        $result = $this->security->isGranted('non_gmail_email');

        static::assertTrue($result);
    }

    #[Test]
    public function customVoterDeniesAccessToGmail(): void
    {
        $this->client->loginUser(
            new InMemoryUser('test_user1@gmail.com', 'test', ['ROLE_USER','ROLE_REGIONAL_ADMIN']),
            'blue'
        );

        $result = $this->security->isGranted('non_gmail_email');

        static::assertFalse($result);
    }

    /**
     * Make this test pass by setting an appropriate access decision strategy in the security bundle config.
     *
     * @see config/packages/security.yaml
     */
    #[Test]
    #[DataProvider('accessDecisionManagerStrategyCanBeConfiguredProvider')]
    public function accessDecisionManagerStrategyCanBeConfigured(string $method, int $status): void
    {
        $this->client->loginUser(
            new InMemoryUser('test_user1', 'test', ['ROLE_USER','ROLE_REGIONAL_ADMIN']),
            'blue'
        );

        $this->client->request($method, '/topic9/blue-firewall/access-controlled');

        static::assertResponseStatusCodeSame($status);
    }

    public static function accessDecisionManagerStrategyCanBeConfiguredProvider(): iterable
    {
        yield ['GET', 403];
        yield ['POST', 200];
    }

    /**
     * Make this test pass by defining a new service.
     *
     * @see config/services.yaml
     */
    #[Test]
    public function accessDecisionManagerIsJustAnotherService(): void
    {
        /** @var AccessDecisionManager $accessDecisionManager */
        $accessDecisionManager = static::getContainer()->get('app.custom_access_decision_manager');

        $result = $accessDecisionManager->decide(
            new TestBrowserToken(
                ['ROLE_USER'],
                new InMemoryUser('test_user1@non-gmail-email.com', 'test', ['ROLE_USER']),
                'anything'
            ),
            ['non_gmail_email'],
        );

        static::assertTrue($result);
    }
}
