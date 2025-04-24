<?php

declare(strict_types=1);

namespace App\Enum;

enum OrderFormValidationGroupEnum: string
{
    case Default = 'Default';
    case Order = 'Order';
    case LightValidation = 'LightValidation';
    case HeavyValidation = 'HeavyValidation';
}
