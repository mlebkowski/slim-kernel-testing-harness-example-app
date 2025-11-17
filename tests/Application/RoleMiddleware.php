<?php

declare(strict_types=1);

namespace Acme\Application;

use Acme\Application\Authorization\Role;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class RoleMiddleware implements MiddlewareInterface {
    public static function of(?Role $role): self {
        return new self($role);
    }

    public function __construct(private Role $role) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        return $handler->handle($request->withAttribute(Role::class, $this->role));
    }
}
