<?php

declare(strict_types=1);

namespace App\Controller\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class HttpTopicController
{
    #[Route('/topic1/request-param/{param}')]
    public function requestParam(Request $request): Response
    {
        return new Response();
    }

    #[Route('/topic1/response-is-unprocessable', methods: ['POST'])]
    public function responseIsUnprocessable(): Response
    {
        return new Response();
    }

    #[Route('/topic1/request-method-is-safe')]
    public function isRequestMethodSafe(Request $request): Response
    {
        return new Response();
    }

    #[Route('/topic1/request-method-is-idempotent')]
    public function isRequestMethodIdempotent(Request $request): Response
    {
        return new Response();
    }

    #[Route('/topic1/cookie')]
    public function cookies(Request $request): Response
    {
        return new Response();
    }

    #[Route('/topic1/content-language-detection'), Route('/topic1/{_locale}/content-language-detection')]
    public function locale(Request $request): Response
    {
        return new Response();
    }
}
