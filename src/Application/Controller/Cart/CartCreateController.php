<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Cart;

use Acme\Application\Http\JsonResponseFactory;
use Acme\Domain\Cart\Cart;
use Acme\Domain\Cart\CartRepository;
use Acme\Domain\User\User;
use Psr\Http\Message\ResponseInterface;

final readonly class CartCreateController {
    public function __construct(
        private CartRepository $carts,
        private CartOutputFactory $cartOutputFactory,
        private JsonResponseFactory $jsonResponseFactory,
    ) {
    }

    public function __invoke(User $user): ResponseInterface {
        $cart = Cart::empty($user);
        $this->carts->save($cart);

        return $this->jsonResponseFactory->create($this->cartOutputFactory->fromEntity($cart));
    }
}
