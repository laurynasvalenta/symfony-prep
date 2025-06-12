<?php

declare(strict_types=1);

namespace App\Transport\Miscellaneous;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\Sync\SyncTransport;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class CustomTransportFactory implements TransportFactoryInterface
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        return new SyncTransport($this->messageBus);
    }

    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'custom-transport://');
    }
}
