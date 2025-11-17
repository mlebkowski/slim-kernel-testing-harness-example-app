<?php

declare(strict_types=1);

namespace Acme\Application\Controller\Product;

final class ProductListInput {
    public int $page = 1;
    public int $perPage = 3;
}
