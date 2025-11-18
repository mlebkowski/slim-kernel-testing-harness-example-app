<?php

declare(strict_types=1);

namespace Acme\Application\CartApi;

use PHPUnit\Framework\Assert;
use WonderNetwork\SlimKernel\Accessor\ArrayAccessor;
use WonderNetwork\SlimKernelTestingHarness\KernelHttpClient\HttpResponseAssertion;

final readonly class CartAssertion {
    public static function of(HttpResponseAssertion $response): self {
        $data = ArrayAccessor::of($response->expectJson());

        return new self(
            id: $data->string('id'),
            userId: $data->string('userId'),
            totalPrice: $data->int('totalPrice'),
            items: CartItemListAssertion::of($data->array('items')),
        );
    }

    public function __construct(
        public string $id,
        private string $userId,
        private int $totalPrice,
        private CartItemListAssertion $items,
    ) {
    }

    public function assertHasTotalPrice(): self {
        Assert::assertGreaterThan(0, $this->totalPrice);

        return $this;
    }

    public function firstItem(): CartItemAssertion {
        return $this->items->first();
    }
}
