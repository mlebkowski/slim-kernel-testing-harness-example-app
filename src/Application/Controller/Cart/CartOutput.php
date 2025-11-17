<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Cart;

use Acme\Domain\Cart\CartItem;

final readonly class CartOutput {
    public function __construct(
        public string $id,
        public string $userId,
        public int $totalPrice,
        /**
         * @var CartItem[]
         */
        public array $items,
    ) {
    }
}
