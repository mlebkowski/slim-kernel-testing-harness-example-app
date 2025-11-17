<?php

declare(strict_types=1);

namespace Acme\Domain\Cart;

use Acme\Domain\Product\ProductCollection;
use Acme\Domain\Product\ProductId;
use Acme\Domain\Product\ProductNotFoundException;
use Acme\Domain\Product\ProductPrice;

final readonly class CartItem {
    public function __construct(
        public ProductId $productId,
        public int $quantity,
    ) {
        if ($quantity < 1 || $quantity > 10) {
            throw new InvalidCartException('Quantity must be between 1 and 10');
        }
    }

    /**
     * @throws InvalidCartException
     */
    public function add(int $quantity): self {
        return new self(
            productId: $this->productId,
            quantity: $quantity + $this->quantity,
        );
    }

    /**
     * @throws InvalidCartException
     */
    public function remove(int $quantity): ?self {
        if ($quantity === $this->quantity) {
            return null;
        }

        if ($quantity > $this->quantity) {
            throw new InvalidCartException('Cannot remove more than exists in the cart');
        }

        return new self(
            productId: $this->productId,
            quantity: $this->quantity - $quantity,
        );
    }

    /**
     * @throws ProductNotFoundException
     */
    public function value(ProductCollection $products): ProductPrice {
        return $products
            ->price($this->productId)
            ->mul($this->quantity);
    }
}
