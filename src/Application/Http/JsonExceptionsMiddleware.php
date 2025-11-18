<?php

declare(strict_types=1);

namespace Acme\Application\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpSpecializedException;

final readonly class JsonExceptionsMiddleware implements MiddlewareInterface {
    public function __construct(private JsonResponseFactory $jsonResponseFactory) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (HttpSpecializedException $e) {
            return $this->jsonResponseFactory->error($e->getMessage(), $e->getCode());
        }
    }
}
