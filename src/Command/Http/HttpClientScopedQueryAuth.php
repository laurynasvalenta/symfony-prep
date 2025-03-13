<?php

declare(strict_types=1);

namespace App\Command\Http;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:test:http-client:header-auth-scoped',
)]
class HttpClientScopedHeaderAuth extends Command
{
    public function __construct(
        private readonly HttpClientInterface $headerAuthHttpClient
    ) {
        parent::__construct();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->headerAuthHttpClient->request(
            'GET',
            'http://nginx/mock-data/authenticate-via-header',
        );

        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
