<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

use PHPUnit\Framework\TestCase;

final class ProductPriceTest extends TestCase {
    public function testAdd(): void {
        self::assertEquals(
            expected: ProductPrice::of(3),
            actual: ProductPrice::of(2)->add(ProductPrice::of(1)),
        );
    }

    public function testMul(): void {
        self::assertEquals(
            expected: ProductPrice::of(120),
            actual: ProductPrice::of(30)->mul(4),
        );
    }
}
