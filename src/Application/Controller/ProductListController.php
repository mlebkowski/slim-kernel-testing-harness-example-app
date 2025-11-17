<?php

declare(strict_types=1);

namespace Acme\Application\Controller;

use Acme\Application\Http\JsonResponseFactory;
use Acme\Domain\Product\InvalidProductException;
use Acme\Domain\Product\Product;
use Acme\Domain\Product\ProductRepository;
use Psr\Http\Message\ResponseInterface;
use WonderNetwork\SlimKernel\Http\Serializer\Payload;
use WonderNetwork\SlimKernel\Http\Serializer\PayloadSource;

final readonly class ProductListController {
    public function __construct(
        private ProductRepository $products,
        private JsonResponseFactory $jsonResponseFactory,
    ) {
    }

    public function __invoke(#[Payload(PayloadSource::Get)] ProductListInput $input): ResponseInterface {

        $products =$this->products->all(page: $input->page, perPage: $input->perPage);

        return $this->jsonResponseFactory->create(ProductOutput::fromMany(...$products));
    }
}
