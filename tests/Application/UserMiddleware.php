<?php

declare(strict_types=1);

namespace Acme\Application;

use Acme\Domain\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class UserMiddleware implements MiddlewareInterface {
    public static function of(?User $user): self {
        return new self($user);
    }

    public function __construct(private ?User $user) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        return $handler->handle($request->withAttribute(User::class, $this->user));
    }
}
