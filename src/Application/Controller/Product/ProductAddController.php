<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Product;

use Acme\Application\Http\JsonResponseFactory;
use Acme\Domain\Product\InvalidProductException;
use Acme\Domain\Product\Product;
use Acme\Domain\Product\ProductRepository;
use Psr\Http\Message\ResponseInterface;
use WonderNetwork\SlimKernel\Http\Serializer\Payload;

final readonly class ProductAddController {
    public function __construct(
        private ProductRepository $products,
        private JsonResponseFactory $jsonResponseFactory,
    ) {
    }

    public function __invoke(#[Payload] ProductInput $input): ResponseInterface {
        // we have a db index at a low level, but let’s check again
        // to handle the error gracefully. Modern frameworks can handle
        // this for us with a UniqueEntity assertion
        if ($this->products->findByName($input->name)) {
            return $this->jsonResponseFactory->error('Product already exists!');
        }

        try {
            $product = Product::new(
                name: $input->name,
                price: $input->price,
            );
        } catch (InvalidProductException $e) {
            // let’s assume domain errors have sensible messages
            return $this->jsonResponseFactory->error($e->getMessage());
        }

        $this->products->save($product);

        return $this->jsonResponseFactory->create(ProductOutput::fromEntity($product));
    }
}
