<?php

declare(strict_types=1);

namespace App\Enum;

enum CarFormValidationGroupEnum: string
{
    case Default = 'Default';
    case Car = 'Car';
    case Vehicle = 'Vehicle';
}
