<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

use App\Model\DemoUser;
use App\Model\Product;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Demonstrates Serializer component functionality including groups and object population.
 */
class ProductManager
{
    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    public function convertFromJsonToProduct(string $json): Product
    {
        return $this->serializer->deserialize(
            $json,
            Product::class,
            'json',
            ['groups' => 'product:write']
        );
    }

    public function updateProductFromJson(Product $product, string $json): Product
    {
        return $this->serializer->deserialize(
            $json,
            Product::class,
            'json',
            [
                'object_to_populate' => $product,
                'groups' => 'product:write',
            ]
        );
    }

    public function convertProductToJson(Product $product): string
    {
        return $this->serializer->serialize(
            $product,
            'json',
            ['groups' => 'product:write']
        );
    }
}
