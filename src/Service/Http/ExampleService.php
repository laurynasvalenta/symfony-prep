<?php

declare(strict_types=1);

namespace App\Service\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExampleService
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function execute(): string
    {
        return '';
    }
}
