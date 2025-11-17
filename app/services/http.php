<?php

declare(strict_types=1);

use Psr\Http\Message\StreamFactoryInterface;
use Slim\Psr7\Factory\StreamFactory;
use function DI\autowire;

return [
    StreamFactoryInterface::class => autowire(StreamFactory::class),
];
