<?php

declare(strict_types=1);

namespace Acme\Application\ProductApi;

use PHPUnit\Framework\Assert;
use WonderNetwork\SlimKernel\Accessor\ArrayAccessor;
use WonderNetwork\SlimKernelTestingHarness\KernelHttpClient\SlimKernelHttpClient;

final readonly class ProductAssertion {
    public static function ofAccessor(SlimKernelHttpClient $httpClient, ArrayAccessor $data): self {
        return new self(
            httpClient: $httpClient,
            id: $data->string('id'),
            name: $data->string('name'),
            price: $data->float('price'),
        );
    }

    public function __construct(
        private SlimKernelHttpClient $httpClient,
        private string $id,
        private string $name,
        private float $price,
    ) {
    }

    public function remove(): void {
        $this->httpClient->delete("/product/{$this->id}");
    }

    public function assertPrice(float $expected): self {
        Assert::assertSame($expected, $this->price);
        return $this;
    }
}
