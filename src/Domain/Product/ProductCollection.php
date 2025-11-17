<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

final readonly class ProductCollection {
    /**
     * @var Product[]
     */
    public array $items;

    public static function empty(): self {
        return new self();
    }

    public function __construct(Product ...$items) {
        $this->items = $items;
    }

    /**
     * @throws ProductNotFoundException
     */
    public function price(ProductId $productId): ProductPrice {
        foreach ($this->items as $item) {
            if ($item->id->equals($productId)) {
                return $item->price;
            }
        }

        throw new ProductNotFoundException();
    }
}
