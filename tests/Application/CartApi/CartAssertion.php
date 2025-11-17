<?php

declare(strict_types=1);

namespace Acme\Application\CartApi;

use WonderNetwork\SlimKernel\Accessor\ArrayAccessor;
use WonderNetwork\SlimKernelTestingHarness\KernelHttpClient\HttpResponseAssertion;
use function WonderNetwork\SlimKernel\Collection\map;

final readonly class CartAssertion {
    public static function of(HttpResponseAssertion $response): self {
        $data = ArrayAccessor::of($response->expectJson());
        return new self(
            id: $data->string('id'),
            userId: $data->requireString('userId'),
            items: CartItemListAssertion::of(
                ...
                map(
                    $data->array('items'),
                    static fn (mixed $item) => CartItemAssertion::of($item),
                ),
            ),
        );
    }

    public function __construct(
        private string $id,
        private string $userId,
        private CartItemListAssertion $items,
    ) {
    }


}
