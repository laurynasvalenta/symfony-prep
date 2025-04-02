<?php

declare(strict_types=1);

namespace App\Service\Loader;

use Symfony\Component\Config\Loader\Loader;

class RoutesLoader extends Loader
{
    public function load(mixed $resource, ?string $type = null): mixed
    {
        return null;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return 'custom_loader' === $type;
    }
}
