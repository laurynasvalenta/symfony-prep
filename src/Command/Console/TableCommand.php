<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:console:table'
)]
class TableCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Age', 'City'])
            ->setRows([
                ['Alice', 24, 'Paris'],
                ['Bob', 35, 'London'],
            ]);

        $table->render();

        return 0;
    }
} 