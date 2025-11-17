<?php

declare(strict_types=1);

namespace Acme\Application\Controller;

use Acme\Domain\Product\Product;
use Acme\Domain\Product\ProductCollection;
use function WonderNetwork\SlimKernel\Collection\map;

final readonly class ProductOutput {
    public static function fromEntity(Product $product): self {
        return new self(
            id: $product->id->value,
            name: $product->name->value,
            price: $product->price->value,
        );
    }

    /**
     * @return self[]
     */
    public static function fromMany(ProductCollection $products): array {
        return map($products->items, self::fromEntity(...));
    }

    public function __construct(
        public string $id,
        public string $name,
        public int $price,
    ) {
    }
}
