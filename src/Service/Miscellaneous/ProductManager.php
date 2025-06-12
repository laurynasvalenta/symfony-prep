<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

use App\Model\Product;
use Symfony\Component\Serializer\SerializerInterface;

class ProductManager
{
    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    public function updateProductFromJson(Product $product, string $json): Product
    {
        return $this->serializer->deserialize(
            $json,
            Product::class,
            'json'
        );
    }

    public function convertProductToJson(Product $product): string
    {
        return $this->serializer->serialize(
            $product,
            'json'
        );
    }
}
