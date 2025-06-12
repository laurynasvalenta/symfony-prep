<?php

declare(strict_types=1);

namespace App\MessageHandler\Miscellaneous;

use App\Message\Miscellaneous\DemoMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DemoMessageHandler
{
    private array $processedMessages = [];

    public function __invoke(DemoMessage $message): void
    {
        $this->processedMessages[] = $message;
    }

    /**
     * @return array<DemoMessage>
     */
    public function getProcessedMessages(): array
    {
        return $this->processedMessages;
    }
}
