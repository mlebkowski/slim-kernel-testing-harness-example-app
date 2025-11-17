<?php

declare(strict_types=1);

namespace Acme\Authorization;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class RequestHandlerStub implements RequestHandlerInterface {
    public static function of(ResponseInterface $response): self {
        return new self($response);
    }

    public function __construct(private ResponseInterface $response) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface {
        return $this->response;
    }
}
