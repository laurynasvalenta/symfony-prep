<?php

declare(strict_types=1);

namespace App\TwigExtension;

use App\Service\Utilities\StringManipulator;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    public static string $dynamicGlobalValue = '';

    public function getGlobals(): array
    {
        return ['dynamicGlobalVariable' => self::$dynamicGlobalValue];
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('*_string_with_*', [StringManipulator::class, 'applyFilter'], ['needs_charset' => true]),
        ];
    }
}
