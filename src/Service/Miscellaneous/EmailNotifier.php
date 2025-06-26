<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

/**
 * Service for sending templated emails using the Mailer component.
 */
class EmailNotifier
{
    /**
     * Sends a templated welcome email to a user.
     */
    public function sendWelcomeEmail(string $userEmail, string $userName): void
    {
    }
} 