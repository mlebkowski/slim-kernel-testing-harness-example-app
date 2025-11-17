<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

use PHPUnit\Framework\TestCase;

class ProductCollectionTest extends TestCase {
    public function testPrice(): void {
        $sut = new ProductCollection(
            $alpha = ProductMother::priced(310),
            $bravo = ProductMother::priced(125)
        );

        self::assertEquals(ProductPrice::of(310), $sut->price($alpha->id));
        self::assertEquals(ProductPrice::of(125), $sut->price($bravo->id));
    }

    public function testPriceOfMissingProduct(): void {
        $this->expectException(ProductNotFoundException::class);
        $sut = ProductCollection::empty();

        $sut->price(ProductId::some());
    }
}
