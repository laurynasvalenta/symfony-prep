<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\Regex;

#[\Attribute]
class ExampleCompound extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Regex(pattern: '/!/', match: false),
            new NotEqualTo('invalid-example1'),
            new NotEqualTo('invalid-example2'),
        ];
    }
}
