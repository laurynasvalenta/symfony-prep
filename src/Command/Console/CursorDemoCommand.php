<?php

declare(strict_types=1);

namespace App\Command\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:console:cursor-demo'
)]
class CursorDemoCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $cursor = new Cursor($output);

        $train = '_________
 |  _  |    __
 | | | |____\/_
 | |_| |       \
 | __  |  _  _  |
 |/  \_|_/ \/ \/
  \__/   \_/\_/';
        $lines = explode("\n", $train);

        while (true) {
            for ($i = 0; $i < 50; $i++) {
                foreach ($lines as $line) {
                    $output->writeln(str_repeat(' ', $i) . $line);
                }

                usleep(100000);

                $cursor->moveUp(count($lines));
                $cursor->clearOutput();
            }
        }

        return Command::SUCCESS;
    }
}

