<?php

declare(strict_types=1);

namespace Acme\Application\CartApi;

use PHPUnit\Framework\Assert;
use WonderNetwork\SlimKernel\Accessor\ArrayAccessor;

final readonly class CartItemAssertion {
    public static function of(ArrayAccessor $data): self {
        return new self(
            productId: $data->requireString('productId'),
            quantity: $data->int('quantity'),
        );
    }

    public function __construct(
        public string $productId,
        public int $quantity,
    ) {
    }

    public function assertProductId(string $productId): self {
        Assert::assertSame($this->productId, $productId);
        return $this;
    }

    public function assertQuantity(int $expected): self {
        Assert::assertSame($this->quantity, $expected);
        return $this;
    }
}
