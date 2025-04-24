<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints\GroupSequenceProvider;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

#[GroupSequenceProvider]
class Order implements GroupSequenceProviderInterface
{
    #[NotBlank(message: 'You must provide product name.', groups: ['LightValidation'])]
    public ?string $productName = null;

    #[NotBlank(message: 'You must provide order quantity.', groups: ['LightValidation'])]
    #[LessThan(value: 1000, message: 'Your order exceeds the stock.', groups: ['HeavyValidation'])]
    public ?int $quantity = null;

    public function getGroupSequence(): array
    {
        return ['LightValidation', 'HeavyValidation'];
    }
}
