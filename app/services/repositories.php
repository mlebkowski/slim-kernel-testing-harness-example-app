<?php

declare(strict_types=1);

use Acme\Domain\Cart\CartRepository;
use Acme\Domain\Product\ProductRepository;
use Acme\Infrastructure\Cart\PdoCartRepository;
use Acme\Infrastructure\Product\PdoProductRepository;
use function DI\autowire;
use function DI\get;

return [
    PdoProductRepository::class => autowire(),
    ProductRepository::class => get(PdoProductRepository::class),
    PdoCartRepository::class => autowire(),
    CartRepository::class => get(PdoCartRepository::class),
];
