<?php

declare(strict_types=1);

namespace App\Controller\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

#[AsController]
class FirewalledPages
{
    #[Route('/topic9/blue-firewall/public-page'), Route('/topic9/green-firewall/public-page')]
    public function page1(Security $security, Request $request): Response
    {
        return new Response(sprintf(
                'Page under %s firewall.',
                $security->getFirewallConfig($request)->getName())
        );
    }

    #[Route('/topic9/blue-firewall/protected-page')]
    #[Route('/topic9/green-firewall/protected-page')]
    public function page2(Security $security, Request $request): Response
    {
        return new Response(sprintf(
                'Protected page under %s firewall.',
                $security->getFirewallConfig($request)->getName())
        );
    }

    #[Route('/topic9/blue-firewall/role-protected-page')]
    public function page3(Security $security): Response
    {
        $roles = $security->getUser()?->getRoles() ?? [];

        if (in_array('ROLE_REGIONAL_ADMIN', $roles) === false) {
            throw new AccessDeniedHttpException('');
        }

        return new Response('Role-protected page.');
    }

    #[Route('/topic9/blue-firewall/special-roles-summary')]
    public function page4(Security $security): Response
    {
        $summary = [
            'Authenticated User' => $security->getUser()?->getUserIdentifier(),
            'Is the user currently impersonating another user' => 'Unknown',
            'Is the user authenticated via the remember me token' => 'Unknown',
            'Is completely authenticated' => 'Unknown',
        ];

        return new Response(implode("\n", array_map(
            fn($key) => "$key: {$summary[$key]}",
            array_keys($summary)
        )));
    }

    #[Route('/topic9/blue-firewall/login', name: 'blue_firewall_login')]
    public function login(Environment $twig, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return new Response($twig->render('topic9/login.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error' => $error,
        ]));
    }

    #[Route('/topic9/blue-firewall/restricted/{role}')]
    public function page5(string $role): Response
    {
        return new Response('Page restricted for ' . $role);
    }

    #[Route('/topic9/blue-firewall/access-controlled')]
    public function page6(): Response
    {
        return new Response('Access Controlled');
    }
}
