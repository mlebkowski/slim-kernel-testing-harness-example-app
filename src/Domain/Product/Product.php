<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

final class Product {
    /**
     * @throws InvalidProductException
     */
    public static function new(string $name, float $price): self {
        return new self(
            id: ProductId::some(),
            name: ProductName::of($name),
            price: ProductPrice::of($price),
        );
    }

    public function __construct(
        public readonly ProductId $id,
        public ProductName $name,
        public ProductPrice $price,
    ) {
    }
}
