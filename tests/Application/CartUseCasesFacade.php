<?php

declare(strict_types=1);

namespace Acme\Application;

use Acme\Application\CartApi\CartAssertion;
use WonderNetwork\SlimKernelTestingHarness\KernelHttpClient\RequestBuilder;
use WonderNetwork\SlimKernelTestingHarness\UseCase\KernelHttpClientUseCase;

final readonly class CartUseCasesFacade {
    use KernelHttpClientUseCase;

    public function create(): CartAssertion {
        return CartAssertion::of(
            $this->httpClient->post('/cart'),
        );
    }

    public function addItem(string $cartId, string $productId, int $quantity = 1): void {
        $this->httpClient->post("/cart/$cartId/item", compact('productId', 'quantity'));
    }

    public function get(string $cartId): CartAssertion {
        return CartAssertion::of(
            $this->httpClient->get("/cart/$cartId"),
        );
    }

    public function removeItem(string $cartId, string $productId, int $quantity): void {
        // I didn’t anticipate I’d want to use payload with DELETE requests:
        $this->httpClient->request(
            RequestBuilder
                ::of(
                    method: "DELETE",
                    uri: "/cart/$cartId/item"
                )
                ->withParsedBody(compact('productId', 'quantity'))
                ->build(),
        );
    }
}
