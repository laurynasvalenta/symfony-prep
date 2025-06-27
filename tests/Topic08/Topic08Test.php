<?php

declare(strict_types=1);

namespace App\Tests\Topic08;

use App\Service\DependencyInjectionTopic\Aggregator;
use App\Service\DependencyInjectionTopic\AnotherServiceWithMultipleDependencies;
use App\Service\DependencyInjectionTopic\Collection;
use App\Service\DependencyInjectionTopic\Connector;
use App\Service\DependencyInjectionTopic\Generator;
use App\Service\DependencyInjectionTopic\Handler;
use App\Service\DependencyInjectionTopic\Manager;
use App\Service\DependencyInjectionTopic\Mapper;
use App\Service\DependencyInjectionTopic\Monitor;
use App\Service\DependencyInjectionTopic\OuterTrackerDecorator;
use App\Service\DependencyInjectionTopic\Parser;
use App\Service\DependencyInjectionTopic\Processor;
use App\Service\DependencyInjectionTopic\Profiler;
use App\Service\DependencyInjectionTopic\Provider;
use App\Service\DependencyInjectionTopic\ServiceWithMultipleDependencies;
use App\Service\DependencyInjectionTopic\SubProducer;
use App\Service\DependencyInjectionTopic\Tracker;
use App\Service\DependencyInjectionTopic\TrackerDecorator;
use App\Service\DependencyInjectionTopic\TrackerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class Topic08Test extends KernelTestCase
{
    private ContainerInterface $extendedTestContainer;
    private ContainerInterface $standardAppContainer;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->extendedTestContainer = static::getContainer();
        $this->standardAppContainer = static::$kernel->getContainer();
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function dependencyCanBeInjectedViaConstructor(): void
    {
        /** @var Manager $service */
        $service = $this->standardAppContainer->get(Manager::class);
     
        static::assertInstanceOf(LoggerInterface::class, $service->getLogger());
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function dependencyCanBeInjectedViaSetter(): void
    {
        /** @var Manager $service */
        $service = $this->standardAppContainer->get(Manager::class);

        static::assertInstanceOf(RouterInterface::class, $service->getRouter());
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function dependencyCanBeInjectedViaPublicProperty(): void
    {
        /** @var Manager $service */
        $service = $this->standardAppContainer->get(Manager::class);

        static::assertInstanceOf(Environment::class, $service->getTwig());
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function lazyServiceIsNotInitiatedWhenNotUsed(): void
    {
        $this->standardAppContainer->get(Handler::class);

        static::assertFalse(Handler::$initiated);
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function lazyServiceIsInitiatedIfUsed(): void
    {
        /** @var Handler $service */
        $service = $this->standardAppContainer->get(Handler::class);
        $service->handle();

        static::assertTrue(Handler::$initiated);
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function serviceCanBeDefinedAsPrivate(): void
    {
        $serviceFromAppContainer = $this->standardAppContainer->get(
            Parser::class,
            ContainerInterface::NULL_ON_INVALID_REFERENCE
        );
        $serviceFromExtendedTestContainer = $this->extendedTestContainer->get(
            Parser::class,
            ContainerInterface::NULL_ON_INVALID_REFERENCE
        );

        static::assertNull($serviceFromAppContainer);
        static::assertInstanceOf(Parser::class, $serviceFromExtendedTestContainer);
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function serviceCanBeDefinedAsSynthetic(): void
    {
        $service = new Processor();

        $this->standardAppContainer->set(Processor::class, $service);

        static::assertInstanceOf(Processor::class, $this->standardAppContainer->get(Processor::class));
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function serviceCanBeDefinedAsNotShared(): void
    {
        $service1 = $this->extendedTestContainer->get(Generator::class);
        $service2 = $this->extendedTestContainer->get(Generator::class);

        static::assertNotSame($service1, $service2);
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function serviceCanInheritConfigurationFromParentService(): void
    {
        /*** @var Connector $service */
        $service = $this->extendedTestContainer->get('simple_service');

        static::assertInstanceOf(Connector::class, $service);
        static::assertInstanceOf(LoggerInterface::class, $service->getLogger());
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function environmentVariableCanBeProcessed(): void
    {
        /** @var Mapper $service */
        $service = $this->extendedTestContainer->get(Mapper::class);

        static::assertSame('Extracted value.', $service->value);
    }

    /**
     * @see config/dependency_injection_topic.yaml
     */
    #[Test]
    public function serviceCanBeDecorated(): void
    {
        /** @var TrackerInterface $service */
        $service = $this->extendedTestContainer->get(TrackerInterface::class);

        $expectedOutput = implode(' ', [OuterTrackerDecorator::class, TrackerDecorator::class, Tracker::class]);

        static::assertEquals($expectedOutput, $service->track());
    }

    #[Test, DataProvider('servicesCanBeInjectedBasedOnTheirTagProvider')]
    public function servicesCanBeInjectedBasedOnTheirTag(string $containerServiceName): void
    {
        /** @var Collection $service */
        $service = $this->extendedTestContainer->get($containerServiceName);

        static::assertSame(['producer1', 'producer2'], array_map(
            static fn ($producer) => $producer->getName(),
            $service->getItems()
        ));
    }

    public static function servicesCanBeInjectedBasedOnTheirTagProvider(): iterable
    {
        /**
         * @see config/dependency_injection_topic.yaml
         */
        yield ['app.first_collection'];
        /**
         * @see src/DependencyInjection/ProducerCollectingCompilerPass.php
         */
        yield ['app.second_collection'];
    }

    #[Test]
    public function serviceCanBeBuiltByStaticFactory(): void
    {
        /** @var Profiler $service */
        $service = $this->extendedTestContainer->get('app.profiler.built_by_static_factory');

        static::assertSame('Service built by static factory.', $service->getInput());
    }

    #[Test]
    public function serviceCanBeBuiltByFactoryService(): void
    {
        /** @var Profiler $service */
        $service = $this->extendedTestContainer->get('app.profiler.built_by_service_factory');

        static::assertSame('Service built by service factory.', $service->getInput());
    }

    /**
     * @see src/DependencyInjection/ClockInjectingCompilerPass.php
     */
    #[Test]
    public function compilerPassCanBeUsedToModifyServiceContainer(): void
    {
        /** @var Aggregator $service */
        $service = $this->extendedTestContainer->get(Aggregator::class);

        static::assertInstanceOf(ClockInterface::class, $service->getClock());
    }

    /**
     * @see src/Kernel.php
     */
    #[Test]
    public function autoconfigurationCanBeConfiguredForInterfaces(): void
    {
        /** @var Collection $service */
        $service = $this->extendedTestContainer->get('app.third_collection');

        static::assertInstanceOf(SubProducer::class, $service->getItems()[0]);
    }

    /**
     * @see src/Kernel.php
     */
    #[Test]
    public function autoconfigurationCanBeConfiguredForAttributes(): void
    {
        /** @var Collection $service */
        $service = $this->extendedTestContainer->get('app.fourth_collection');

        static::assertInstanceOf(Provider::class, $service->getItems()[0]);
    }

    /**
     * @see src/Service/DependencyInjectionTopic/ServiceWithMultipleDependencies.php
     */
    #[Test]
    public function serviceLocatorCanBeUsed(): void
    {
        /** @var ServiceWithMultipleDependencies $service */
        $service = $this->extendedTestContainer->get(ServiceWithMultipleDependencies::class);

        static::assertInstanceOf(LoggerInterface::class, $service->getLogger());
        static::assertInstanceOf(ClockInterface::class, $service->getClock());
        static::assertNull($service->getRouter());
    }

    /**
     * @see src/Service/DependencyInjectionTopic/AnotherServiceWithMultipleDependencies.php
     */
    #[Test]
    public function serviceMethodsSubscriberTrait(): void
    {
        /** @var AnotherServiceWithMultipleDependencies $service */
        $service = $this->extendedTestContainer->get(AnotherServiceWithMultipleDependencies::class);

        static::assertInstanceOf(LoggerInterface::class, $service->getLogger());
        static::assertInstanceOf(ClockInterface::class, $service->getClock());
    }

    /**
     * @see src/DependencyInjection/LoggerRemovingExtension.php
     */
    #[Test]
    public function loadExtensionCannotBeUsedToModifyPreviouslyCreatedServices(): void
    {
        /** @var Monitor $service */
        $service = $this->extendedTestContainer->get(Monitor::class);

        static::assertSame('value', $this->standardAppContainer->getParameter('new_parameter'));
        static::assertNull($service->getLogger());
    }
}

