<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

use PHPUnit\Framework\TestCase;

class ProductPriceTest extends TestCase {
    public function testTooMuchPrecision(): void {
        $this->expectException(InvalidProductException::class);
        $this->expectExceptionMessage('Price has to high precision: 12.995');

        ProductPrice::of(12.995);
    }
}
