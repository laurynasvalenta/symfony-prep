<?php

declare(strict_types=1);

namespace App\Command\Http;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test:http-client:standalone',
)]
class HttpClientStandalone extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}
