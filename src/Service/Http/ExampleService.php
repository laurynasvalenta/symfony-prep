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
        $result = $this->client->request('GET', '/example')->getContent() . PHP_EOL;
        $result .= $this->client->request('GET', '/example1')->getContent();

        return $result;
    }
}
