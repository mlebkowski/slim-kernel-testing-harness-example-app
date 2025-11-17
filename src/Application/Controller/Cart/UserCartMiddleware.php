<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Cart;

use Acme\Domain\Cart\Cart;
use Acme\Domain\Cart\CartId;
use Acme\Domain\Cart\CartNotFoundException;
use Acme\Domain\Cart\CartRepository;
use Acme\Domain\Cart\InvalidCartException;
use Acme\Domain\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use WonderNetwork\SlimKernel\Http\RouteArgument;

final readonly class UserCartMiddleware implements MiddlewareInterface {
    public function __construct(private CartRepository $carts) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $id = RouteArgument::of($request)->requireString('id');
        try {
            $cartId = CartId::of($id);
        } catch (InvalidCartException) {
            throw new HttpBadRequestException($request);
        }

        try {
            $cart = $this->carts->get($cartId);
        } catch (CartNotFoundException) {
            throw new HttpNotFoundException($request);
        }

        /** @var ?User $user */
        $user = $request->getAttribute(User::class);

        if ($user?->id !== $cart->userId) {
            throw new HttpNotFoundException($request);
        }

        return $handler->handle($request->withAttribute(Cart::class, $cart));
    }
}
