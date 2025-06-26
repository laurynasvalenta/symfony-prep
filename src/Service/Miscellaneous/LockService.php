<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

use Symfony\Component\Lock\Key;

class LockService
{
    public function acquireReadLock(Key $key): bool
    {
        return false;
    }

    public function acquireWriteLock(Key $key): bool
    {
        return false;
    }
}
