<?php

declare(strict_types=1);

namespace App\Tests\Topic8;

use App\Configuration\Configuration1;
use App\Configuration\Configuration2;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    /**
     * @see src/Configuration/Configuration1.php
     */
    #[Test]
    public function xmlConfigIsFixed(): void
    {
        $configurationDefinition = new Configuration1();

        $processor = new Processor();

        $processedConfiguration = $processor->processConfiguration(
            $configurationDefinition,
            [
                [
                    'name' => [
                        'name1',
                        'name2',
                    ],
                ],
                [
                    'names' => [
                        'name3',
                    ],
                ]
            ],
        );

        static::assertSame([
            'names' => ['name1', 'name2', 'name3'],
        ], $processedConfiguration);
    }

    /**
     * @see src/Configuration/Configuration2.php
     */
    #[Test]
    public function validationIsPerformedByTheDefinition(): void
    {
        $configurationDefinition = new Configuration2();

        $processor = new Processor();

        $processedConfiguration = $processor->processConfiguration(
            $configurationDefinition,
            [
                [
                    'type' => 'pdf',
                ],
                [
                    'type' => 'gif',
                ]
            ],
        );

        static::assertSame(['type' => 'Other type: gif'], $processedConfiguration);
    }
}
