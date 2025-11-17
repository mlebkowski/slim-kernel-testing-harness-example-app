<?php

declare(strict_types=1);

namespace Acme\Domain\Cart;

use Acme\Domain\Product\ProductId;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase {
    public function testAdd(): void {
        $sut = new CartItem(
            productId: ProductId::some(),
            quantity: 1,
        );

        $actual = $sut->add(3);
        self::assertSame(4, $actual->quantity);
    }

    public function testAddRespectsLimits(): void {
        $this->expectException(InvalidCartException::class);
        $this->expectExceptionMessage('Quantity must be between 1 and 10');
        $sut = new CartItem(
            productId: ProductId::some(),
            quantity: 5,
        );

        $sut->add(10);
    }
}
