<?php

declare(strict_types=1);

namespace Acme\Application\CartApi;

use Acme\Application\CartApi;
use WonderNetwork\SlimKernel\Accessor\ArrayAccessor;

final readonly class CartItemAssertion {
    public static function of(mixed $item): self {
        $data = ArrayAccessor::of($item);
        return new self(
            productId: $data->requireString('product_id'),
            quantity: $data->int('quantity'),
        );
    }

    public function __construct(
        public string $productId,
        public int $quantity,
    ) {
    }
}
