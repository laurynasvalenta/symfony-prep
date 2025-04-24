<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints\GroupSequenceProvider;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

class Order
{
    public ?string $productName = null;
    public ?int $quantity = null;
}
