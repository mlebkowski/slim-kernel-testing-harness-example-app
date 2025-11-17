<?php

declare(strict_types=1);

namespace Acme\Application\ProductApi;

use PHPUnit\Framework\Assert;
use WonderNetwork\SlimKernelTestingHarness\Assertion\JsonListResponseAssertion;

final readonly class ProductListAssertion {
    use JsonListResponseAssertion;

    public function named(string $name): ProductAssertion {
        $result = $this->filteredBy('name', $name);
        if (0 === count($result->items->data)) {
            Assert::assertNotCount(
                expectedCount: 0,
                haystack: $result->items->data,
                message: "Expected product '$name' to be listed.",
            );
        }

        return $result->first();
    }

    public function first(): ProductAssertion {
        return ProductAssertion::ofAccessor(
            $this->items->getFirstItemDataAccessor(),
        );
    }
}
