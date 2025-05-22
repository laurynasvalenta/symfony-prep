<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:console:colored-output'
)]
class ColoredOutputCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('outputOption');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $option = [
            'normal' => OutputInterface::OUTPUT_NORMAL,
            'raw' => OutputInterface::OUTPUT_RAW,
        ];

        $output->writeln('<fg=red>Red output</>', $option[$input->getArgument('outputOption')]);

        return Command::SUCCESS;
    }
}
