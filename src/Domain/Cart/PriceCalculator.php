<?php

declare(strict_types=1);

namespace Acme\Domain\Cart;

use Acme\Domain\Product\ProductNotFoundException;
use Acme\Domain\Product\ProductPrice;
use Acme\Domain\Product\ProductRepository;
use function WonderNetwork\SlimKernel\Collection\map;

/**
 * It requires `getMany(...)` method on the repo to avoid n+1
 */
final readonly class PriceCalculator {
    public function __construct(private ProductRepository $products) {
    }

    /**
     * ignoring overflows
     * @throws ProductNotFoundException
     */
    public function calculate(CartItem ...$items): ProductPrice {
        $products = $this->products->getMany(
            ...
            map(
                $items,
                static fn (CartItem $item) => $item->productId,
            ),
        );

        return array_reduce(
            $items,
            static fn (ProductPrice $sum, CartItem $item) => $sum->add($item->value($products)),
            ProductPrice::zero(),
        );
    }
}
