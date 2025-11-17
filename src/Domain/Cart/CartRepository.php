<?php

declare(strict_types=1);

namespace Acme\Domain\Cart;

interface CartRepository {
    /**
     * @throws CartNotFoundException
     */
    public function get(CartId $id): Cart;

    public function save(Cart $cart): void;
}
