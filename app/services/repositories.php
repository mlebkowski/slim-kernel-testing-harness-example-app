<?php

declare(strict_types=1);

use Acme\Domain\Product\ProductRepository;
use Acme\Infrastructure\Product\PdoProductRepository;
use function DI\autowire;
use function DI\get;

return [
    PdoProductRepository::class => autowire(),
    ProductRepository::class => get(PdoProductRepository::class),
];
