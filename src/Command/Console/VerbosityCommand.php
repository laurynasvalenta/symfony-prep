<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:console:verbosity',
    description: 'Demonstrates verbosity levels',
)]
class VerbosityCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Normal output', OutputInterface::VERBOSITY_NORMAL);
        $output->writeln('Verbose output', OutputInterface::VERBOSITY_VERBOSE);
        $output->writeln('Very verbose output', OutputInterface::VERBOSITY_VERY_VERBOSE);
        $output->writeln('Debug output', OutputInterface::VERBOSITY_DEBUG);

        if ($output->isVerbose()) {
            $output->writeln('This is also displayed in verbose mode');
        }

        if ($output->isVeryVerbose()) {
            $output->writeln('This is also displayed in very verbose mode');
        }

        if ($output->isDebug()) {
            $output->writeln('This is also displayed in debug mode');
        }

        return Command::SUCCESS;
    }
}
