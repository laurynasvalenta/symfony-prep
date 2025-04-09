<?php

declare(strict_types=1);

namespace App\Service\Utilities;

use Twig\Extension\RuntimeExtensionInterface;

class StringManipulator implements RuntimeExtensionInterface
{
    public function applyFilter(string $action, string $subject, string $valueOnWhichTheFilterIsApplied, int $timesToRepeat): string
    {
        return '';
    }
}