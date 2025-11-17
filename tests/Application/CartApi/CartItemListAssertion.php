<?php

declare(strict_types=1);

namespace Acme\Application\CartApi;

use WonderNetwork\SlimKernelTestingHarness\Assertion\ListAssertion;

final readonly class CartItemListAssertion {
    use ListAssertion;

    public function first(): CartItemAssertion {
        return CartItemAssertion::of($this->items->getFirstItemDataAccessor());
    }
}
