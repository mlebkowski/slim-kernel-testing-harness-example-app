<?php

declare(strict_types=1);

namespace Acme\Application;

use Acme\Application\CartApi\CartAssertion;
use WonderNetwork\SlimKernelTestingHarness\UseCase\KernelHttpClientUseCase;

final readonly class CartUseCasesFacade {
    use KernelHttpClientUseCase;

    public function create(): CartAssertion {
        return CartAssertion::of(
            $this->httpClient->post('/cart'),
        );
    }

    public function addItem(string $cartId, string $productId, int $quantity): void {
        $this->httpClient->post("/cart/$cartId/item", compact('productId', 'quantity'));
    }

    public function get(string $cartId): CartAssertion {
        return CartAssertion::of(
            $this->httpClient->get("/cart/$cartId"),
        );
    }
}
