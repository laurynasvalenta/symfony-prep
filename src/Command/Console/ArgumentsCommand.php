<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:console:arguments',
    description: 'Demonstrates command arguments',
)]
class ArgumentsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('countries', InputArgument::REQUIRED & InputArgument::IS_ARRAY);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('Countries: %s!', implode(', ', $input->getArgument('countries'))));

        return Command::SUCCESS;
    }
} 