<?php

declare(strict_types=1);

namespace App\Controller\SymfonyArchitecture;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class SymfonyArchitectureController
{
    #[Route('/topic2/page1')]
    public function page1(): Response
    {
        return new Response('Page 1 response');
    }

    public function page2(): Response
    {
        return new Response('Page 2 response');
    }

    #[Route('/topic2/page3')]
    public function page3(string $argumentInjectedViaEventListener = ''): Response
    {
        return new Response('Page 3 response: ' . $argumentInjectedViaEventListener);
    }

    #[Route('/topic2/page4')]
    public function page4(): string
    {
        return 'Page 4 response';
    }

    #[Route('/topic2/page5')]
    public function page5(): string
    {
        return 'Page 5 response';
    }

    #[Route('/topic2/error')]
    public function error(): Response
    {
        throw new \Exception('An error occurred');
    }

    #[Route('/topic2/server-shutdown')]
    public function serverShutdown(): Response
    {
        return new Response('Demonstrating the danger of the terminate event.' . PHP_EOL, 200, ['X-Accel-Buffering' => 'no']);
    }

    #[Route('/topic2/forbidden')]
    public function forbidden(): Response
    {
        throw new AccessDeniedHttpException('Access denied');
    }
}
