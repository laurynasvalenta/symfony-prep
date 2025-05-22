<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:console:progress-bar',
)]
class ProgressBarCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $progressBar = new ProgressBar($output, 5);

        $progressBar->start();

        for ($i = 0; $i < 5; $i++) {
            $progressBar->advance();
            $progressBar->display();
        }

        $progressBar->finish();

        return 0;
    }
}