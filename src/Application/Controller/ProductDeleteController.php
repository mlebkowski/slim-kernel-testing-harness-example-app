<?php

declare(strict_types=1);

namespace Acme\Application\Controller;

use Acme\Domain\Product\ProductId;
use Acme\Domain\Product\ProductNotFoundException;
use Acme\Domain\Product\ProductRepository;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ResponseFactory;

final readonly class ProductDeleteController {
    public function __construct(
        private ResponseFactory $responseFactory,
        private ProductRepository $products,
    ) {
    }

    public function __invoke(string $id): ResponseInterface {
        try {
            $product = $this->products->get(ProductId::of($id));

            $this->products->delete($product);
        } catch (ProductNotFoundException) {
            // we don’t care for that particular exception!
            // this shows how setting error boundaries is important
            // `get` throws a specific exception, and `save` potentially
            // throws a different kind, but they don’t overlap
        }

        // let’s not talk about semantics of the http status codes
        return $this->responseFactory->createResponse(StatusCodeInterface::STATUS_ACCEPTED);
    }
}
