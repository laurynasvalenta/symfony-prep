<?php

declare(strict_types=1);

namespace App\Tests\Topic2;

use App\EventListener\TestEventListener;
use DemoBundle\Provider\DateProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;

/*
 * This is a demonstration test for Symfony Certification Topic 1 (Symfony Architecture).
 *
 * Please DO NOT edit this file.
 */
class Topic2Test extends WebTestCase
{
    #[Test]
    public function bundleIsRegisteredSuccessfully(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        static::assertTrue($container->has(DateProvider::class));
    }

    #[Test]
    public function serviceContainerParameterIsSet(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        static::assertEquals('demo', $container->getParameter('demo_bundle.parameter'));
    }

    #[Test]
    public function eventListenerIsConfiguredCorrectly(): void
    {
        $event = new GenericEvent();

        /** @var TraceableEventDispatcher $eventDispatcher */
        $eventDispatcher = static::getContainer()->get('event_dispatcher');
        $eventDispatcher->dispatch($event, 'test_event');

        static::assertEquals([TestEventListener::class . '::__invoke'], $this->getCalledListenerNames());
    }

    /**
     * Make the test pass by editing the src/Subscriber/RequestSubscriber.php file.
     */
    #[Test]
    public function requestEventIsIntercepted(): void
    {
        $client = static::createClient(['debug' => true]);

        $client->request('GET', '/topic2/page1', [], [], [
            'HTTP_X_INTERCEPT' => 'request1',
        ]);

        $triggeredEvents = $this->getTriggeredEvents();

        static::assertContains('kernel.request', $triggeredEvents);
        static::assertNotContains('kernel.controller', $triggeredEvents);
        static::assertNotContains('kernel.controller_arguments', $triggeredEvents);
    }

    /**
     * Make the test pass by editing the src/Subscriber/ControllerSubscriber.php file.
     */
    #[Test]
    public function controllerEventIsIntercepted(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic2/page1', [], [], [
            'HTTP_X_INTERCEPT' => 'request2',
        ]);

        static::assertEquals('Page 2 response', $client->getResponse()->getContent());
    }

    /**
     * Make the test pass by editing the src/Subscriber/ControllerArgumentsSubscriber.php file.
     */
    #[Test]
    public function controllerArgumentsEventIsIntercepted(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic2/page3');

        static::assertEquals('Page 3 response: Argument injected via event listener', $client->getResponse()->getContent());
    }

    /**
     * Make the test pass by editing the src/Subscriber/ViewSubscriber.php file.
     */
    #[Test]
    public function viewEventIsIntercepted(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic2/page4');

        static::assertEquals('Page 4 response', $client->getResponse()->getContent());
    }

    /**
     * Make the test pass by editing the src/Subscriber/ResponseSubscriber.php file.
     */
    #[Test]
    public function responseEventIsIntercepted(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic2/page5');

        static::assertEquals('Page 5 response with content added by a listener', $client->getResponse()->getContent());
    }

    /**
     * Make the test pass by editing the src/Subscriber/ExceptionSubscriber.php file.
     */
    #[Test]
    public function exceptionEventIsIntercepted(): void
    {
        $client = static::createClient();

        $client->request('GET', '/topic2/error');

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isCacheable());
    }

    #[Test]
    public function error403PageIsCustomised(): void
    {
        $client = static::createClient(['debug' => false]);

        $client->request('GET', '/topic2/forbidden');

        static::assertSame(403, $client->getResponse()->getStatusCode());
        static::assertSame('This is a customised error page for status code 403.', $client->getResponse()->getContent());
    }

    /**
     * @return string[]
     */
    protected function getTriggeredEvents(): array
    {
        /** @var TraceableEventDispatcher $eventDispatcher */
        $eventDispatcher = static::getContainer()->get('debug.event_dispatcher');

        return array_values(array_unique(array_map(function ($group) {
            return $group['event'];
        }, $eventDispatcher->getCalledListeners())));
    }
    /**
     * @return string[]
     */
    protected function getCalledListenerNames(): array
    {
        /** @var TraceableEventDispatcher $eventDispatcher */
        $eventDispatcher = static::getContainer()->get('debug.event_dispatcher');

        return array_values(array_unique(array_map(function ($group) {
            return $group['pretty'];
        }, $eventDispatcher->getCalledListeners())));
    }
}
