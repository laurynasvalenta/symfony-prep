<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * Service for sending templated emails using the Mailer component.
 */
class EmailNotifier
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    /**
     * Sends a templated welcome email to a user.
     */
    public function sendWelcomeEmail(string $userEmail, string $userName): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@example.com')
            ->to($userEmail)
            ->subject('Welcome to our platform!')
            ->htmlTemplate('topic13/welcome_email.html.twig')
            ->context([
                'userName' => $userName,
            ]);

        $this->mailer->send($email);
    }
} 