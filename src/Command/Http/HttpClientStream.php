<?php

declare(strict_types=1);

namespace App\Command\Http;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

#[AsCommand(
    name: 'app:test:http-client:stream',
)]
class HttpClientStream extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = HttpClient::create();

        $response = $client->request('GET', 'http://nginx/mock-data/streamed-content');

        foreach ($client->stream($response) as $chunk) {
            if ($chunk->isLast() === false) {
                $output->writeln('Downloading content...');

                continue;
            }

            $output->writeln($response->getContent());
        }

        return Command::SUCCESS;
    }
}
