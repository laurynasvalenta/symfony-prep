<?php

declare(strict_types=1);

namespace App\Controller\MockData;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class MockController
{
    public const string SECRET_API_KEY = 'secret-api-key-value';

    #[Route('/mock-data/simple-content')]
    public function simpleContent(): Response
    {
        return new Response('Hello, world!');
    }

    #[Route('/mock-data/authenticate-via-header')]
    public function authenticateViaHeader(Request $request): Response
    {
        if ($request->headers->get('X-API-Key') !== self::SECRET_API_KEY)   {
            throw new UnauthorizedHttpException('ApiKey realm="example", header="X-API-Key"');
        }
        
        return new Response('Authenticated via header');
    }

    #[Route('/mock-data/authenticate-via-query-param')]
    public function authenticateViaQueryParam(Request $request): Response
    {
        if ($request->query->get('key') !== self::SECRET_API_KEY)   {
            throw new UnauthorizedHttpException('ApiKey realm="example", param="key"');
        }

        return new Response('Authenticated via query param');
    }

    #[Route('/mock-data/streamed-content')]
    public function streamedContent(): Response
    {
        $streamedResponse = new StreamedResponse(function () {
            for ($i = 0; $i < 3; $i++) {
                echo sprintf('Part %d of the content.' . PHP_EOL, $i);
                flush();
                sleep(1);
            }
        });
        $streamedResponse->headers->set('X-Accel-Buffering', 'no');
        $streamedResponse->send();

        return $streamedResponse;
    }

    #[Route('/mock-data/cached-content'), Cache(maxage: 600, public: true)]
    public function cachedContent(): Response
    {
        return new Response(microtime());
    }
}