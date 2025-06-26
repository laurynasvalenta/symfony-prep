<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\LockFactory;

class LockService
{
    public static $cache = [];

    public function __construct(
        private LockFactory $lockFactory
    ) {
    }

    public function acquireReadLock(Key $key): bool
    {
        return $this->lockFactory->createLockFromKey($key, 300, false)->acquireRead();
    }

    public function acquireWriteLock(Key $key): bool
    {
        return $this->lockFactory->createLockFromKey($key, 300, false)->acquire();
    }
}
