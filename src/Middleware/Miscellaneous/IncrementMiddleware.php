<?php

declare(strict_types=1);

namespace App\Middleware\Miscellaneous;

use App\Message\Miscellaneous\DemoMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class IncrementMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        
        if ($message instanceof DemoMessage) {
            $message->value++;
        }
        
        return $stack->next()->handle($envelope, $stack);
    }
}
