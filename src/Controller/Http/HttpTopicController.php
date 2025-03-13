<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class Topic1Controller
{
    #[Route('/topic1/request-param/{param}')]
    public function requestParam(Request $request): Response
    {
        return new Response('suffixed-' . $request->attributes->get('param'));
    }

    #[Route('/topic1/response-is-unprocessable', methods: ['POST'])]
    public function responseIsUnprocessable(): Response
    {
        return new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Route('/topic1/request-method-is-safe')]
    public function isRequestMethodSafe(Request $request): Response
    {
        return new Response($request->isMethodSafe() ? 'Yes' : 'No');
    }

    #[Route('/topic1/request-method-is-idempotent')]
    public function isRequestMethodIdempotent(Request $request): Response
    {
        return new Response($request->isMethodIdempotent() ? 'Yes' : 'No');
    }

    #[Route('/topic1/cookie')]
    public function cookies(Request $request): Response
    {
        return new Response('suffixed-' . $request->cookies->get('flavor'));
    }

    #[Route('/topic1/content-language-detection'), Route('/topic1/{_locale}/content-language-detection')]
    public function locale(Request $request): Response
    {
        return new Response($request->getLocale());
    }

    public function multipleContentTypes(Request $request): Response
    {
        $contentType = $request->getAcceptableContentTypes();

        return new Response($request->query->get('flavor'));
    }
}
