<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:console:divide'
)]
class DivideCommand extends Command
{
    protected function configure()
    {
        $this
            ->addArgument('dividend')
            ->addArgument('divisor');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln((string)(((int)$input->getArgument('dividend')) / ((int)$input->getArgument('divisor'))));

        return Command::SUCCESS;
    }
} 