<?php

declare(strict_types=1);

namespace Acme\Application\CartApi;

final readonly class CartItemListAssertion {
    public static function of(CartItemAssertion ...$items): self {
        return new self($items);
    }

    /**
     * @param CartItemAssertion[] $items
     */
    public function __construct(public array $items) {
    }
}
