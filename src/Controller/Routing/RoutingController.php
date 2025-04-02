<?php

declare(strict_types=1);

namespace App\Controller\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class RoutingController
{
    public function route1(): Response
    {
        return new Response('route1');
    }

    #[Route("/topic4/route2")]
    public function route2(): Response
    {
        return new Response('route2');
    }

    #[Route("/topic4/priority/{slug}")]
    public function lowerPriority(): Response
    {
        return new Response('With param');
    }

    #[Route("/topic4/priority/static")]
    public function higherPriority(): Response
    {
        return new Response('With static text');
    }

    #[Route("/topic4/with-param1/{param}")]
    public function routeWithParam(string $param): Response
    {
        return new Response('Inlined param requirements. Param: ' . $param);
    }

    #[Route("/topic4/with-param2/{param}")]
    public function routeWithParam2(string $param): Response
    {
        return new Response('Route requirements configured via a dedicated property. Param: ' . $param);
    }

    #[Route("/topic4/default-param1/{param}")]
    public function defaultParam1(string $param): Response
    {
        return new Response('Param: ' . $param);
    }

    #[Route("/topic4/default-param2/{param}")]
    public function defaultParam2(string $param): Response
    {
        return new Response('Param: ' . $param);
    }

    #[Route("/topic4/default-param3/{param}")]
    public function defaultParam3(string $param): Response
    {
        return new Response('Param: ' . $param);
    }

    #[Route("/topic4/link-target", name: 'topic4-link-target')]
    #[Route("/topic4/generated-link")]
    public function generatedLink(): Response
    {
        return new Response(
            sprintf('<a href="#">Link</a>')
        );
    }

    #[Route("/topic4/internal-route-attributes")]
    public function internalRouteAttributes(Request $request): Response
    {
        return new Response('Locale: ' . $request->getLocale());
    }

    #[Route("/topic4/match-by-host")]
    public function matchByHost(): Response
    {
        return new Response('Matched by host');
    }

    public function customLoadedRoute(): Response
    {
        return new Response('Custom loaded route');
    }
}
