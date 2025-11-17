<?php

declare(strict_types=1);

namespace Acme\Domain\Cart;

use Acme\Domain\Product\ProductMother;
use Acme\Domain\Product\ProductPrice;
use Acme\Domain\Product\ProductRepositoryStub;
use Acme\User\UserMother;
use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase {
    public function testCalculatorWorks(): void {
        $productAlpha = ProductMother::priced(199);
        $productBravo = ProductMother::priced(299);
        $productCharlie = ProductMother::priced(399);
        $sut = new PriceCalculator(
            ProductRepositoryStub::givenProductsExists(
                $productAlpha,
                $productBravo,
                $productCharlie
            ),
        );

        $actual = Cart::empty(UserMother::some())
            ->add($productAlpha->id, 1)
            ->add($productBravo->id, 2)
            ->add($productCharlie->id, 3)
            ->sum($sut);

        self::assertEquals(ProductPrice::of(1994), $actual);
    }
}
