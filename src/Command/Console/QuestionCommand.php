<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:console:question'
)]
class QuestionCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
    }
} 