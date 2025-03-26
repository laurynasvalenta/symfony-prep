<?php

declare(strict_types=1);

namespace App\Controller\Controller;

use App\Form\SimpleFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExamplesFromAbstractController
{
    #[Route('/topic3/router')]
    public function router(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/router2')]
    public function router2(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/main-request-checker')]
    public function mainRequestChecker(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/forward-to-main-request-checker')]
    public function forwardToMainRequestChecker(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/http-kernel')]
    public function httpKernel(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/serializer')]
    public function serializer(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/authorization')]
    public function authorization(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/protected')]
    public function protected(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/twig1')]
    public function twig1(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/twig2')]
    public function twig2(): string
    {
        return '';
    }

    #[Route('/topic3/form')]
    public function form(): Response
    {
        return new Response(SimpleFormType::class);
    }

    #[Route('/topic3/token-storage')]
    public function tokenStorage(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/csrf-token')]
    public function csrfTokenCheck(): Response
    {
        return new Response('');
    }

    #[Route('/topic3/container-parameters')]
    public function containerParameters(): Response
    {
        return new Response('');
    }
}
