<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

interface ProductRepository {
    public function get(string $id): Product;

    /**
     * @return Product[]
     */
    public function all(int $page, int $perPage): array;

    public function save(Product $product): void;

    public function findByName(string $name): ?Product;
}
