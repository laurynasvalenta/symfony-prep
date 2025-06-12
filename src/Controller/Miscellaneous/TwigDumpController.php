<?php

declare(strict_types=1);

namespace App\Controller\Miscellaneous;

use App\Model\DemoUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TwigDumpController extends AbstractController
{
    #[Route('/topic13/twig-dump')]
    public function dump(): Response
    {
        $user = new DemoUser(
            name: 'John Doe',
            email: 'john@example.com',
            age: 30,
            roles: ['ROLE_USER', 'ROLE_ADMIN']
        );

        // Pass the user object to the template for dumping
        return $this->render('miscellaneous/dump.html.twig', [
            'user' => $user,
        ]);
    }
}
