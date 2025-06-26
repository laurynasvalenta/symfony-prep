<?php

declare(strict_types=1);

namespace App\Controller\Miscellaneous;

use App\Service\Miscellaneous\EmailNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Demonstrates email functionality using the Mailer component.
 */
class EmailController extends AbstractController
{
    #[Route('/topic13/send-welcome-email', methods: ['POST'])]
    public function sendWelcomeEmail(Request $request, EmailNotifier $emailNotifier): Response
    {
        $emailNotifier->sendWelcomeEmail('test@example.com', 'John Doe');

        return new Response('Welcome email sent successfully!');
    }
} 