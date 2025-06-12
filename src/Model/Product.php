<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

class Product
{
    public function __construct(
        #[Groups(['product:read'])]
        public int $id = 0,

        #[Groups(['product:read', 'product:write'])]
        public string $sku = '',

        #[Groups(['product:read', 'product:write'])]
        public string $name = '',

        #[Groups(['product:read', 'product:write'])]
        #[SerializedName('cost')]
        public int $price = 0
    ) {
    }
}
