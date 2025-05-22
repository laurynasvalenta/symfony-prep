<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:console:basic',
    description: 'A basic console command example',
)]
class BasicCommand extends Command
{
    public static bool $constructorHasBeenCalled = false;

    public function __construct()
    {
        parent::__construct();

        static::$constructorHasBeenCalled = true;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Hello, this is a basic console command!');

        return Command::SUCCESS;
    }
} 