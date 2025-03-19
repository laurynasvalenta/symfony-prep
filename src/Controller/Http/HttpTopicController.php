<?php

declare(strict_types=1);

namespace App\Controller\Http;

use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class HttpTopicController
{
    #[Route('/topic1/request-param/{param}')]
    public function requestParam(string $param): Response
    {
        return new Response('suffixed-' . $param);
    }

    #[Route('/topic1/response-is-unprocessable', methods: ['POST'])]
    public function responseIsUnprocessable(): Response
    {
        return new Response(
            'This is an unprocessable response',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
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
        return new Response(
            'suffixed-' . $request->cookies->get('flavor')
        );
    }

    #[Route('/topic1/content-language-detection'), Route('/topic1/{_locale}/content-language-detection')]
    public function locale(Request $request): Response
    {



        return new Response();
    }

    #[Route('/topic1/preferred-content-type')]
    public function preferredContentTypeResolution(Request $request): Response
    {
        $response = new Response('', 200, ['Content-Type' => AcceptHeader::fromString($request->headers->get('Accept'))->first()->getValue()]);
        return $response;
    }

    #[Route('/topic1/preferred-safe')]
    public function safePreference(Request $request): Response
    {
        if ($request->preferSafeContent()) {
            $response = new Response('Only safe content.', 200);
            $response->setContentSafe();

            return $response;
        }

        $response = new Response('Anything.');
        return $response;
    }
}
