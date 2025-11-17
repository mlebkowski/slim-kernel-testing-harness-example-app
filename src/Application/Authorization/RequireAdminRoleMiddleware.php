<?php

declare(strict_types=1);

namespace Acme\Application\Authorization;

use Acme\Application\Http\JsonResponseFactory;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Let’s assume some authentication flow which fills out this attribute
 */
final readonly class RequireAdminRoleMiddleware implements MiddlewareInterface {
    public function __construct(private JsonResponseFactory $responseFactory) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $role = $request->getAttribute(Role::class);
        if (Role::Admin !== $role) {
            return $this->responseFactory->create(
                data: ['message' => 'Forbidden'],
                code: StatusCodeInterface::STATUS_FORBIDDEN,
            );
        }

        return $handler->handle($request);
    }
}
