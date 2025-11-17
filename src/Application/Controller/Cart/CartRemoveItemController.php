<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Cart;

use Acme\Application\Http\JsonResponseFactory;
use Acme\Domain\Cart\Cart;
use Acme\Domain\Cart\CartRepository;
use Acme\Domain\Cart\InvalidCartException;
use Acme\Domain\Product\ProductId;
use Psr\Http\Message\ResponseInterface;
use WonderNetwork\SlimKernel\Http\Serializer\Payload;

final readonly class CartRemoveItemController {
    public function __construct(
        private CartRepository $carts,
        private CartOutputFactory $cartOutputFactory,
        private JsonResponseFactory $jsonResponseFactory,
    ) {
    }

    public function __invoke(Cart $cart, #[Payload] CartItemInput $input): ResponseInterface {
        try {
            $cart = $cart->remove(ProductId::of($input->productId), $input->quantity);
        } catch (InvalidCartException $e) {
            return $this->jsonResponseFactory->error($e->getMessage());
        }

        $this->carts->save($cart);

        return $this->jsonResponseFactory->create($this->cartOutputFactory->fromEntity($cart));
    }
}
