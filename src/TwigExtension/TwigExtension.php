<?php

declare(strict_types=1);

namespace App\TwigExtension;

use App\Service\Utilities\StringManipulator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    public static string $dynamicGlobalValue = '';

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('*_string_with_*', [StringManipulator::class, 'applyFilter'], []),
        ];
    }
}
