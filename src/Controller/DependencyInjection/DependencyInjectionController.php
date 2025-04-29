<?php

declare(strict_types=1);

namespace App\Controller\DependencyInjection;

use App\Service\DependencyInjectionTopic\Aggregator;
use App\Service\DependencyInjectionTopic\AnotherServiceWithMultipleDependencies;
use App\Service\DependencyInjectionTopic\Generator;
use App\Service\DependencyInjectionTopic\Mapper;
use App\Service\DependencyInjectionTopic\Monitor;
use App\Service\DependencyInjectionTopic\Parser;
use App\Service\DependencyInjectionTopic\ServiceWithMultipleDependencies;
use App\Service\DependencyInjectionTopic\TrackerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class DependencyInjectionController
{
    public function __construct(
        private readonly ?Parser $parser = null,
        private readonly ?Generator $generator = null,
        private readonly ?Mapper $mapper = null,
        private readonly ?Aggregator $aggregator = null,
        private readonly ?ServiceWithMultipleDependencies $serviceWithMultipleDependencies = null,
        private readonly ?AnotherServiceWithMultipleDependencies $anotherServiceWithMultipleDependencies = null,
        private readonly ?Monitor $monitor = null,
        private readonly ?TrackerInterface $tracker = null,
    ) {
    }

    #[Route('/dependency-injection', name: 'app_dependency_injection')]
    public function __invoke(): Response
    {
        return new Response(
            'Dependency Injection Example:' . implode(
                ', ',
                [
                    spl_object_hash($this->generator),
                    spl_object_hash($this->parser),
                    spl_object_hash($this->mapper),
                    spl_object_hash($this->aggregator),
                    spl_object_hash($this->serviceWithMultipleDependencies),
                    spl_object_hash($this->anotherServiceWithMultipleDependencies),
                    spl_object_hash($this->monitor),
                    spl_object_hash($this->tracker),
                ]
            )
        );
    }
}
