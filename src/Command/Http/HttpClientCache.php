<?php

declare(strict_types=1);

namespace App\Command\Http;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;

#[AsCommand(
    name: 'app:test:http-client:cache',
)]
class HttpClientCache extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $store = new Store('/tmp/');
        $client = HttpClient::create();
        $client = new CachingHttpClient($client, $store);

        $response = $client->request('GET', 'http://nginx/mock-data/cached-content');

        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
