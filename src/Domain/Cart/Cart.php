<?php

declare(strict_types=1);

namespace Acme\Domain\Cart;

use Acme\Domain\Product\ProductId;
use Acme\Domain\Product\ProductNotFoundException;
use Acme\Domain\Product\ProductPrice;
use Acme\Domain\User\User;

final readonly class Cart {
    public static function empty(User $user): self {
        return new self(
            id: CartId::some(),
            userId: $user->id,
            items: [],
        );
    }

    public function __construct(
        public CartId $id,
        public string $userId,
        /**
         * @var CartItem[]
         */
        public array $items,
    ) {
    }

    /**
     * @throws InvalidCartException
     */
    public function add(ProductId $product, int $quantity): self {
        $items = [];
        $exists = false;
        foreach ($this->items as $item) {
            if ($item->productId->equals($product)) {
                $exists = true;
                $item = $item->add($quantity);
            }
            $items[] = $item;
        }

        if (false === $exists) {
            $items[] = new CartItem($product, $quantity);
        }

        return new self(
            id: $this->id,
            userId: $this->userId,
            items: $items,
        );
    }

    /**
     * @throws ProductNotFoundException
     */
    public function sum(PriceCalculator $calculator): ProductPrice {
        return $calculator->calculate(...$this->items);
    }
}
