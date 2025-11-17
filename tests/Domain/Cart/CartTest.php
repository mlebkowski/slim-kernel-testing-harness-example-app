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

    public function testLimit(): void {
        $this->expectException(CartItemLimitReachedException::class);

        Cart::empty(UserMother::some())
            ->add(ProductId::some(), quantity: 1)
            ->add(ProductId::some(), quantity: 2)
            ->add(ProductId::some(), quantity: 3)
            ->add(ProductId::some(), quantity: 4);
    }

    public function testDelete(): void {
        $cart = Cart::empty(UserMother::some());

        $productAlpha = ProductId::some();
        $productBravo = ProductId::some();

        $actual = $cart
            ->add($productAlpha, quantity: 1)
            ->add($productAlpha, quantity: 2)
            ->add($productBravo, quantity: 3)
            ->remove($productAlpha, quantity: 3)
            ->remove($productBravo, quantity: 1)
            ->items;

        self::assertEquals(
            expected: [
                new CartItem(
                    productId: $productBravo,
                    quantity: 2,
                ),
            ],
            actual: $actual,
        );
    }
}
