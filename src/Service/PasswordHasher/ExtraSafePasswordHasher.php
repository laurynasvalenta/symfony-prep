<?php

declare(strict_types=1);

namespace App\Service\PasswordHasher;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class ExtraSafePasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private PasswordHasherInterface $passwordHasherFactory,
    ) {
    }

    public function hash(#[\SensitiveParameter] string $plainPassword): string
    {
        return strrev($this->passwordHasherFactory->hash($plainPassword));
    }

    public function verify(string $hashedPassword, #[\SensitiveParameter] string $plainPassword): bool
    {
        return $this->passwordHasherFactory->verify(strrev($hashedPassword), $plainPassword);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        return $this->passwordHasherFactory->needsRehash(strrev($hashedPassword));
    }
}

