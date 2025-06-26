<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Service demonstrating ICU select and plural functionality using Symfony Translation component.
 */
class TranslationService
{
    public function __construct(private TranslatorInterface $translator) {}

    /**
     * Demonstrates ICU select functionality for gender-based translations.
     */
    public function translateWithIcuSelect(string $gender, string $name): string
    {
        return $this->translator->trans('greeting_gender', [
            'gender' => $gender,
            'name' => $name,
        ]);
    }

    /**
     * Demonstrates ICU plural functionality for count-based translations.
     */
    public function translateWithIcuPlural(int $count): string
    {
        return $this->translator->trans('item_count', [
            'count' => $count,
        ]);
    }
} 