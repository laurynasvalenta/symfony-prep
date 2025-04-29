<?php

declare(strict_types=1);

namespace App\Service\DependencyInjectionTopic;

use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class AnotherServiceWithMultipleDependencies implements ServiceSubscriberInterface
{
    use ServiceMethodsSubscriberTrait;

    #[SubscribedService]
    public function getClock(): ?ClockInterface
    {
        return $this->container->get(__METHOD__);
    }

    #[SubscribedService]
    public function getLogger(): ?LoggerInterface
    {
        return $this->container->get(__METHOD__);
    }
}
