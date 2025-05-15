<?php

declare(strict_types=1);

namespace App\Controller\HttpCaching;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
class HttpCachingController
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    #[Route('/topic10/private-cacheable-response')]
    public function privateCacheableResponse(): Response
    {
        $response = new Response('This response is private and cacheable: ' . microtime());

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    #[Route('/topic10/public-cacheable-response')]
    public function publicCacheableResponse(): Response
    {
        $response = new Response('This response is public and cacheable: ' . microtime());

        $response->setMaxAge(3600);

        return $response;
    }

    #[Route('/topic10/no-store-response')]
    public function noStoreResponse(): Response
    {
        $response = new Response('This response is marked as no-store: '  . microtime());

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    #[Route('/topic10/validatable-response')]
    public function validatableResponse(): Response
    {
        $response = new Response('This response is validatable: ' . microtime());

        $response->setPrivate();

        return $response;
    }

    #[Route('/topic10/page-contains-esi-fragment')]
    public function pageContainsEsiFragment(): Response
    {
        return new Response(
            $this->twig->render(
                'topic10/page-contains-esi-fragment.html.twig',
                [
                    'value' => microtime(),
                ]
            )
        );
    }
}
