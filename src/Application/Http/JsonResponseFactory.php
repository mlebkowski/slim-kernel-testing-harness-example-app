<?php

declare(strict_types=1);

namespace Acme\Application\Http;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;

final readonly class JsonResponseFactory {
    public function __construct(
        private ResponseFactory $factory,
        private StreamFactoryInterface $streamFactory,
    ) {
    }

    /**
     * Convenience method
     */
    public function error(string $message, int $code = StatusCodeInterface::STATUS_BAD_REQUEST): ResponseInterface {
        // see, I could’ve used `compact()` but didn’t! :D
        return $this->create(['message' => $message], $code);
    }

    public function create(mixed $data, int $code = StatusCodeInterface::STATUS_OK): ResponseInterface {
        return $this->factory
            ->createResponse($code)
            ->withHeader('Content-Type', 'application/json')
            ->withBody(
                $this->streamFactory->createStream(
                    json_encode($data, JSON_THROW_ON_ERROR),
                ),
            );
    }
}
