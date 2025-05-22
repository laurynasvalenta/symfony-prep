<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:console:options',
    description: 'Demonstrates command options',
)]
class OptionsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('port');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('Port: %s!', $input->getOption('port')));

        return Command::SUCCESS;
    }
} 