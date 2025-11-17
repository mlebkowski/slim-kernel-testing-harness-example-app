<?php

declare(strict_types=1);

namespace Acme\Domain\Cart;

use Acme\Domain\Product\ProductId;
use Acme\User\UserMother;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase {
    public function testAdd(): void {
        $cart = Cart::empty(UserMother::some());

        $productAlpha = ProductId::some();
        $productBravo = ProductId::some();

        $actual = $cart
            ->add($productAlpha, quantity: 1)
            ->add($productAlpha, quantity: 2)
            ->add($productBravo, quantity: 3)
            ->items;

        self::assertEquals(
            expected: [
                new CartItem(
                    productId: $productAlpha,
                    quantity: 3,
                ),
                new CartItem(
                    productId: $productBravo,
                    quantity: 3,
                ),
            ],
            actual: $actual,
        );
    }
}
