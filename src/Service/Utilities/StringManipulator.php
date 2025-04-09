<?php

declare(strict_types=1);

namespace App\Service\Utilities;

use Twig\Extension\RuntimeExtensionInterface;

class StringManipulator implements RuntimeExtensionInterface
{
    public function applyFilter(string $charset, string $action, string $subject, string $valueOnWhichTheFilterIsApplied, int $timesToRepeat): string
    {
        if ($action === 'prefix') {
            return str_repeat($subject, $timesToRepeat) . ' ' . $valueOnWhichTheFilterIsApplied;
        }

        if ($action === 'suffix') {
            return $valueOnWhichTheFilterIsApplied . ' ' . str_repeat($subject, $timesToRepeat);
        }

        if ($action === 'replace' && $subject === 'charset') {
            return $charset;
        }

        return '';
    }
}