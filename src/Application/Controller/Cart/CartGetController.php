<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Cart;

use Acme\Application\Http\JsonResponseFactory;
use Acme\Domain\Cart\Cart;
use Psr\Http\Message\ResponseInterface;

final readonly class CartGetController {
    public function __construct(
        private CartOutputFactory $cartOutputFactory,
        private JsonResponseFactory $jsonResponseFactory,
    ) {
    }

    public function __invoke(Cart $cart): ResponseInterface {
        return $this->jsonResponseFactory->create($this->cartOutputFactory->fromEntity($cart));
    }
}
