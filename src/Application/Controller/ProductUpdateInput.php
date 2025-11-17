<?php

declare(strict_types=1);

namespace Acme\Application\Controller;

class ProductUpdateInput {
    public ?string $name = null;
    public ?float $price = null;
}
