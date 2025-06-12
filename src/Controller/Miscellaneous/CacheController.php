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
        $cache = $this->getCache();
        $cacheItem = $cache->getItem('psr6_result');

        if (!$cacheItem->isHit()) {
            $cacheItem->set('Result: PSR-6 ' . microtime());
            $cache->save($cacheItem);
        }

        return new Response($cacheItem->get());
    }

    #[Route('/topic13/cache-contracts')]
    public function cacheContracts(): Response
    {
        $cache = $this->getCache();

        $result = $cache->get('contracts_result', function (ItemInterface $item): string {
            $item->expiresAfter(3600);
            return 'Result: Contracts ' . microtime();
        });

        return new Response($result);
    }

    #[Route('/topic13/cache-early-expiration')]
    public function cacheEarlyExpiration(): Response
    {
        $cache = $this->getCache();

        $result = $cache->get('stampede_result', function (ItemInterface $item): string {
            $item->expiresAfter(3600);
            return 'Result: Early Expiration ' . microtime();
        }, INF);

        return new Response($result);
    }

    #[Route('/topic13/process-demo')]
    public function processDemo(): Response
    {
        $process = new Process(['php', 'bin/console', 'debug:dotenv']);
        $process->setTimeout(10);
        $process->run();

        $output = $process->getOutput();
        if (empty($output)) {
            $output = 'Process demo - command executed successfully';
        }

        return new Response($output);
    }

    private function getCache(): ArrayAdapter
    {
        return self::$cache ??= new ArrayAdapter();
    }
}
