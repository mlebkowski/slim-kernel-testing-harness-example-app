<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

final readonly class Product {
    /**
     * @throws InvalidProductException
     */
    public static function new(string $name, int $price): self {
        return new self(
            id: ProductId::some(),
            name: ProductName::of($name),
            price: ProductPrice::of($price),
        );
    }

    public function __construct(
        public ProductId $id,
        public ProductName $name,
        public ProductPrice $price,
    ) {
    }

    /**
     * I wish we had with-ers in PHP, similarly to .NET for example
     */
    public function named(ProductName $name): self {
        return new self(
            id: $this->id,
            name: $name,
            price: $this->price,
        );
    }

    public function priced(ProductPrice $price): self {
        return new self(
            id: $this->id,
            name: $this->name,
            price: $price,
        );
    }
}
