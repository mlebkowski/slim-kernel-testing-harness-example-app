<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

final readonly class ProductMother {
    public static function priced(int $price): Product {
        return Product::new(
            name: bin2hex(random_bytes(8)),
            price: $price,
        );
    }
}
