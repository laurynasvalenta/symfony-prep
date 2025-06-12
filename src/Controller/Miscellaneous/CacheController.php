<?php

declare(strict_types=1);

namespace App\Controller\Miscellaneous;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Demonstrates cache and Process component functionality.
 */
class CacheController extends AbstractController
{
    private static ?ArrayAdapter $cache = null;

    #[Route('/topic13/psr6-cache')]
    public function psr6Cache(): Response
    {
        return new Response('Result: PSR-6 ' . microtime());
    }

    #[Route('/topic13/cache-contracts')]
    public function cacheContracts(): Response
    {
        $result = 'Result: Contracts ' . microtime();

        return new Response($result);
    }

    #[Route('/topic13/cache-early-expiration')]
    public function cacheEarlyExpiration(): Response
    {
        $cache = $this->getCache();

        $result = $cache->get('stampede_result', function (ItemInterface $item): string {
            $item->expiresAfter(3600);
            return 'Result: Early Expiration ' . microtime();
        });

        return new Response($result);
    }

    #[Route('/topic13/process-demo')]
    public function processDemo(): Response
    {
        return new Response('');
    }

    private function getCache(): ArrayAdapter
    {
        return self::$cache ??= new ArrayAdapter();
    }
}
