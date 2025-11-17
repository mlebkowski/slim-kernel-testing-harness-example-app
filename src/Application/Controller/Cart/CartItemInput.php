<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Cart;

final class CartItemInput {
    public string $productId;
    public int $quantity;
}
