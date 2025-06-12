<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Serializer\Attribute\Groups;

class DemoUser
{
    public function __construct(
        #[Groups(['public', 'admin'])]
        private readonly string $name = 'Unknown',
        
        #[Groups(['admin'])]
        private readonly string $email = 'unknown@example.com',
        
        #[Groups(['public', 'admin'])]
        private readonly int $age = 0,
        
        #[Groups(['admin'])]
        private readonly array $roles = ['ROLE_USER'],
        
        #[Groups(['admin'])]
        private readonly string $internalId = '',
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getInternalId(): string
    {
        return $this->internalId;
    }
}
