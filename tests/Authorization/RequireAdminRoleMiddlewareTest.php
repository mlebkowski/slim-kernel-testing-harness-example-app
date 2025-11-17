<?php

declare(strict_types=1);

namespace Acme\Authorization;

use Acme\Application\Authorization\RequireAdminRoleMiddleware;
use Acme\Application\Authorization\Role;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class RequireAdminRoleMiddlewareTest extends TestCase {
    public function testPassesWithRole(): void {
        $requestFactory = new ServerRequestFactory();
        $responseFactory = new ResponseFactory();
        $handler = RequestHandlerStub::of($responseFactory->createResponse());

        $request = $requestFactory
            ->createServerRequest('GET', '/')
            ->withAttribute(Role::class, Role::Admin);

        $sut = new RequireAdminRoleMiddleware($responseFactory);
        $actual = $sut->process($request, $handler);

        self::assertSame(StatusCodeInterface::STATUS_OK, $actual->getStatusCode());
    }

    public function testBailsWithoutRole(): void {
        $requestFactory = new ServerRequestFactory();
        $responseFactory = new ResponseFactory();
        $handler = RequestHandlerStub::of($responseFactory->createResponse());

        $request = $requestFactory->createServerRequest('GET', '/');

        $sut = new RequireAdminRoleMiddleware($responseFactory);
        $actual = $sut->process($request, $handler);

        self::assertSame(StatusCodeInterface::STATUS_FORBIDDEN, $actual->getStatusCode());
    }

    public function testBailsWithInvalidRole(): void {
        $requestFactory = new ServerRequestFactory();
        $responseFactory = new ResponseFactory();
        $handler = RequestHandlerStub::of($responseFactory->createResponse());

        $request = $requestFactory
            ->createServerRequest('GET', '/')
            ->withAttribute(Role::class, Role::None);

        $sut = new RequireAdminRoleMiddleware($responseFactory);
        $actual = $sut->process($request, $handler);

        self::assertSame(StatusCodeInterface::STATUS_FORBIDDEN, $actual->getStatusCode());
    }
}
