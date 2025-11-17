<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Cart;

use Acme\Domain\Cart\Cart;
use Acme\Domain\Cart\CartItem;
use Acme\Domain\Cart\PriceCalculator;
use function WonderNetwork\SlimKernel\Collection\map;

final readonly class CartOutputFactory {
    public function __construct(private PriceCalculator $priceCalculator) {
    }

    public function fromEntity(Cart $cart): CartOutput {
        return new CartOutput(
            id: $cart->id->value,
            userId: $cart->userId,
            totalPrice: $this->priceCalculator->calculate(...$cart->items)->value,
            items: map(
                $cart->items,
                static fn (CartItem $item) => CartItemOutput::fromEntity($item),
            ),
        );
    }
}
