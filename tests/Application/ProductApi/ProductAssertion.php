<?php

declare(strict_types=1);

namespace Acme\Application\ProductApi;

use PHPUnit\Framework\Assert;
use WonderNetwork\SlimKernel\Accessor\ArrayAccessor;

final readonly class ProductAssertion {
    public static function ofAccessor(ArrayAccessor $data): self {
        return new self(
            id: $data->string('id'),
            name: $data->string('name'),
            price: $data->float('price'),
        );
    }

    public function __construct(
        private string $id,
        private string $name,
        private float $price,
    ) {
    }



    public function assertPrice(float $expected): self {
        Assert::assertSame($expected, $this->price);
        return $this;
    }
}
