<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Compound;

class ExampleCompound extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [];
    }
}
