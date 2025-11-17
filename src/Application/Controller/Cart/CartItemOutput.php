<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Cart;

use Acme\Domain\Cart\CartItem;

final readonly class CartItemOutput {
    public static function fromEntity(CartItem $item): self {
        return new self(
            productId: $item->productId->value,
            quantity: $item->quantity,
        );
    }

    public function __construct(
        public string $productId,
        public int $quantity,
    ) {
    }
}
