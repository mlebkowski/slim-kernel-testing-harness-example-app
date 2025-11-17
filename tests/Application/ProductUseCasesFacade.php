<?php

declare(strict_types=1);

namespace Acme\Application;

use WonderNetwork\SlimKernelTestingHarness\KernelHttpClient\HttpResponseAssertion;
use WonderNetwork\SlimKernelTestingHarness\UseCase\KernelHttpClientUseCase;

final readonly class ProductUseCasesFacade {
    use KernelHttpClientUseCase;

    public function add(string $name, int $price): HttpResponseAssertion {
        // shh, compact was not safe in the PHP 4 days, but it’s
        // perfectly safe now, once we have all this static analysis
        // watching over our backs
        return $this->httpClient->post('/product', compact('name', 'price'));
    }

    public function list(int $page = null, int $perPage = null): ProductApi\ProductListAssertion {
        return ProductApi\ProductListAssertion::ofJsonResponse(
            $this->httpClient->get('/product?'.http_build_query(compact('page', 'perPage'))),
        );
    }

    public function update(string $id, string $name = null, int $price = null): HttpResponseAssertion {
        return $this->httpClient->patch("/product/$id", compact('name', 'price'));
    }
}
