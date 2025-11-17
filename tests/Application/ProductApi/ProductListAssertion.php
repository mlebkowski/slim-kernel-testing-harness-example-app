<?php

declare(strict_types=1);

namespace Acme\Application\ProductApi;

use PHPUnit\Framework\Assert;
use WonderNetwork\SlimKernelTestingHarness\Assertion\JsonListResponseAssertion;

final readonly class ProductListAssertion {
    use JsonListResponseAssertion;

    public function named(string $name): self {
        return $this->filteredBy('name', $name);
    }

    public function assertExists(): self {
        Assert::assertNotCount(
            expectedCount: 0,
            haystack: $this->items->data,
            message: "No such product exists.",
        );
        return $this;
    }

    public function assertDoesNotExist(): self {
        Assert::assertCount(
            expectedCount: 0,
            haystack: $this->items->data,
            message: "Product wasn’t expected to exist.",
        );

        return $this;
    }

    public function first(): ProductAssertion {
        return ProductAssertion::ofAccessor(
            httpClient: $this->response->httpClient,
            data: $this->items->getFirstItemDataAccessor(),
        );
    }

    public function assertForbidden(): void {
        $this->response->assertForbidden();
    }
}
