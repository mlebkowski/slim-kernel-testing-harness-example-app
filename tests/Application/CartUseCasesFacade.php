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
}
