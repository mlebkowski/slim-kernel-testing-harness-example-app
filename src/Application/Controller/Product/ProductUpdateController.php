<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Product;

use Acme\Application\Http\JsonResponseFactory;
use Acme\Domain\Product\InvalidProductException;
use Acme\Domain\Product\ProductId;
use Acme\Domain\Product\ProductName;
use Acme\Domain\Product\ProductNotFoundException;
use Acme\Domain\Product\ProductPrice;
use Acme\Domain\Product\ProductRepository;
use Psr\Http\Message\ResponseInterface;
use WonderNetwork\SlimKernel\Http\Serializer\Payload;

final readonly class ProductUpdateController {
    public function __construct(
        private JsonResponseFactory $jsonResponseFactory,
        private ProductRepository $products,
    ) {
    }

    public function __invoke(string $id, #[Payload] ProductUpdateInput $input): ResponseInterface {
        try {
            $product = $this->products->get(ProductId::of($id));
        } catch (ProductNotFoundException) {
            // here we care!
            return $this->jsonResponseFactory->error('Product not found');
        }

        try {
            if (isset($input->name)) {
                $product = $product->named(ProductName::of($input->name));
            }

            if (isset($input->price)) {
                $product = $product->priced(ProductPrice::of($input->price));
            }
        } catch (InvalidProductException $e) {
            // I’m saving time. A better error handling is in order…
            // but then again, I’d use a framework to build this API
            // instead of implementing it from scratch
            return $this->jsonResponseFactory->error($e->getMessage());
        }

        $this->products->save($product);

        return $this->jsonResponseFactory->create(ProductOutput::fromEntity($product));
    }
}
