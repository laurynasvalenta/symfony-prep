<?php

declare(strict_types=1);

namespace App\Tests\Topic11;

use App\Command\Console\BasicCommand;
use App\Command\Console\ExecuteFunctionCommand;
use App\Subscriber\ConsoleEventSubscriber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;
use Throwable;

/**
 * This is a demonstration test for Symfony Certification Topic 11 (Console).
 */
class Topic11Test extends KernelTestCase
{
    private Application $application;
    
    protected function setUp(): void
    {
        BasicCommand::$constructorHasBeenCalled = false;

        ConsoleEventSubscriber::$commandEventTriggered = false;
        ConsoleEventSubscriber::$terminateEventTriggered = false;
        ConsoleEventSubscriber::$errorEventTriggered = false;

        self::bootKernel();

        $this->application = new Application(static::$kernel);
        $this->application->setAutoExit(false);
    }

    /**
     * @see src/Command/Console/BasicCommand.php
     */
    #[Test]
    public function customCommandIsRegistered(): void
    {
        $commandTester = new CommandTester($this->application->find('list'));
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();

        static::assertStringContainsString('app:console:basic', $output);
    }

    /**
     * @see src/Command/Console/BasicCommand.php
     */
    #[Test]
    public function configurationViaAttributeAllowsListingTheCommandWithoutInitiatingTheService(): void
    {
        $commandTester = new CommandTester($this->application->find('list'));
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();

        static::assertStringContainsString('app:console:basic', $output);
        static::assertFalse(BasicCommand::$constructorHasBeenCalled);
    }

    /**
     * @see src/Command/Console/ExecuteFunctionCommand.php
     */
    #[Test]
    public function configurationViaConfigureMethodResultsInMandatoryInitiation(): void
    {
        $commandTester = new CommandTester($this->application->find('list'));
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();

        static::assertStringContainsString('app:console:execute-function', $output);
        static::assertStringContainsString('A command to execute a function', $output);
        static::assertTrue(ExecuteFunctionCommand::$constructorHasBeenCalled);
    }

    /**
     * @see src/Command/Console/ArgumentsCommand.php
     */
    #[Test]
    public function argumentModesCanBeCombined(): void
    {
        $commandTester = new CommandTester($this->application->find('app:console:arguments'));
        $commandTester->execute(['countries' => ['Lithuania', 'Poland', 'Germany', 'Croatia']]);

        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();
        static::assertStringContainsString('Countries: Lithuania, Poland, Germany, Croatia', $output);
    }

    /**
     * @see src/Command/Console/OptionsCommand.php
     */
    #[Test]
    public function optionCanHaveDefaultValue(): void
    {
        $commandTester = new CommandTester($this->application->find('app:console:options'));
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();
        static::assertStringContainsString('Port: 8080!', $output);
    }

    /**
     * @see src/Command/Console/ColoredOutputCommand.php
     */
    #[Test]
    public function outputCanBeFormattedForConsoleWithColors(): void
    {
        $commandTester = new CommandTester($this->application->find('app:console:colored-output'));
        $commandTester->execute(['outputOption' => 'normal'], ['decorated' => true]);

        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();
        static::assertSame("\033[31mRed output\033[39m\n", $output);
    }

    /**
     * @see src/Command/Console/ColoredOutputCommand.php
     */
    #[Test]
    public function outputCanBeFormattedRaw(): void
    {
        $commandTester = new CommandTester($this->application->find('app:console:colored-output'));
        $commandTester->execute(['outputOption' => 'raw'], ['decorated' => true]);

        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();
        static::assertSame("<fg=red>Red output</>\n", $output);
    }

    /**
     * @see src/Command/Console/TableCommand.php
     */
    #[Test]
    public function tableHelperCanBeUsed(): void
    {
        $commandTester = new CommandTester($this->application->find('app:console:table'));
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();
        static::assertMatchesRegularExpression('/\\|\s*Name\s.*\\|\s*Age\s.*\\|\s*City\s.*\\|/', $output);
        static::assertMatchesRegularExpression('/\\|\s*Alice\s.*\\|\s*24\s.*\\|\s*Paris\s.*\\|/', $output);
        static::assertMatchesRegularExpression('/\\|\s*Bob\s.*\\|\s*35\s.*\\|\s*London\s.*\\|/', $output);
    }

    /**
     * @see src/Command/Console/ProgressBarCommand.php
     *
     * Check the app:console:cursor-demo command
     *
     * @see src/Command/Console/CursorDemoCommand.php
     */
    #[Test]
    public function progressRepaintsTheTerminal(): void
    {
        $expectedOutput = ' 0/5 [>---------------------------]   0%';
        $expectedOutput .= "\x1b[1G\x1b[2K";
        $expectedOutput .= ' 1/5 [=====>----------------------]  20%';
        $expectedOutput .= "\x1b[1G\x1b[2K";
        $expectedOutput .= ' 2/5 [===========>----------------]  40%';
        $expectedOutput .= "\x1b[1G\x1b[2K";
        $expectedOutput .= ' 3/5 [================>-----------]  60%';
        $expectedOutput .= "\x1b[1G\x1b[2K";
        $expectedOutput .= ' 4/5 [======================>-----]  80%';
        $expectedOutput .= "\x1b[1G\x1b[2K";
        $expectedOutput .= ' 5/5 [============================] 100%';

        $commandTester = new CommandTester($this->application->find('app:console:progress'));
        $commandTester->execute([], ['decorated' => true]);

        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();
        static::assertSame($expectedOutput, $output);
    }

    /**
     * @see src/Command/Console/QuestionCommand.php
     */
    #[Test]
    public function questionHelperIsUsedCorrectly(): void
    {
        $commandTester = new CommandTester($this->application->find('app:console:question'));
        $commandTester->setInputs(['Juergen', 'Berlin']);
        
        $commandTester->execute([]);
        $output = $commandTester->getDisplay();

        static::assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
        static::assertStringContainsString('Name: Juergen', $output);
        static::assertStringContainsString('City: Berlin', $output);
    }

    /**
     * @see src/Command/Console/VerbosityCommand.php
     */
    #[Test, DataProvider('verbosityLevelsProvider')]
    public function verbosityLevelsWorkCorrectly(int $verbosity, array $expectedStrings, array $notExpectedStrings): void
    {
        $commandTester = new CommandTester($this->application->find('app:console:verbosity'));
        $commandTester->execute([], ['verbosity' => $verbosity]);
        $output = $commandTester->getDisplay();
        
        foreach ($expectedStrings as $expected) {
            static::assertStringContainsString($expected, $output);
        }
        
        foreach ($notExpectedStrings as $notExpected) {
            static::assertStringNotContainsString($notExpected, $output);
        }

        static::assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @see src/Command/Console/DivideCommand.php
     * @see src/Subscriber/ConsoleEventSubscriber.php
     */
    #[Test]
    public function consoleEventsAreTriggeredByApplicationTester(): void
    {
        try {
            $e = null;
            $applicationTester = new ApplicationTester($this->application);
            $applicationTester->run(['command' => 'app:console:divide', 'dividend' => 10, 'divisor' => 0], ['decorated' => false]);
        } catch (Throwable $e) {
        }

        static::assertNotNull($e);
        static::assertTrue(ConsoleEventSubscriber::$commandEventTriggered);
        static::assertTrue(ConsoleEventSubscriber::$terminateEventTriggered);
        static::assertTrue(ConsoleEventSubscriber::$errorEventTriggered);
    }

    /**
     * @see src/Command/Console/DivideCommand.php
     * @see src/Subscriber/ConsoleEventSubscriber.php
     */
    #[Test]
    public function consoleEventsAreNotTriggeredByCommandTester(): void
    {
        $commandTester = new CommandTester($this->application->find('app:console:divide'));

        $commandTester->execute(['dividend' => 10, 'divisor' => 2]);

        static::assertFalse(ConsoleEventSubscriber::$commandEventTriggered);
        static::assertFalse(ConsoleEventSubscriber::$terminateEventTriggered);
    }

    /**
     * @return iterable<string, array{0: int, 1: array<int, string>, 2: array<int, string>}>
     */
    public static function verbosityLevelsProvider(): iterable
    {
        yield 'quiet' => [
            OutputInterface::VERBOSITY_QUIET,
            [],
            ['Normal output', 'Verbose output', 'Very verbose output', 'Debug output'],
        ];
        
        yield 'normal' => [
            OutputInterface::VERBOSITY_NORMAL,
            ['Normal output'],
            ['Verbose output', 'Very verbose output', 'Debug output'],
        ];
        
        yield 'verbose' => [
            OutputInterface::VERBOSITY_VERBOSE,
            ['Normal output', 'Verbose output'],
            ['Very verbose output', 'Debug output'],
        ];
        
        yield 'very verbose' => [
            OutputInterface::VERBOSITY_VERY_VERBOSE,
            ['Normal output', 'Verbose output', 'Very verbose output'],
            ['Debug output'],
        ];
        
        yield 'debug' => [
            OutputInterface::VERBOSITY_DEBUG,
            ['Normal output', 'Verbose output', 'Very verbose output', 'Debug output'],
            [],
        ];
    }
} 