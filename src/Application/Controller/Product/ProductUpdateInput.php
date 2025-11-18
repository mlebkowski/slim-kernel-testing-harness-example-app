<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Product;

final class ProductUpdateInput {
    public ?string $name = null;
    public ?int $price = null;
}
