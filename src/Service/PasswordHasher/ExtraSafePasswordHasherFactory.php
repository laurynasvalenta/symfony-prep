<?php

declare(strict_types=1);

namespace App\Service\PasswordHasher;

use Symfony\Component\PasswordHasher\Hasher\Pbkdf2PasswordHasher;

class ExtraSafePasswordHasherFactory
{
    public function __invoke(): ExtraSafePasswordHasher
    {
        return new ExtraSafePasswordHasher(new Pbkdf2PasswordHasher());
    }
}

