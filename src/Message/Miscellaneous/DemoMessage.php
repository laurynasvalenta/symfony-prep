<?php

declare(strict_types=1);

namespace App\Message\Miscellaneous;

final class DemoMessage
{
    public function __construct(
        public int $value = 0
    ) {
    }
}
