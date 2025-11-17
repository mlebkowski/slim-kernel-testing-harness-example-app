<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

interface ProductRepository {
    /**
     * @throws ProductNotFoundException
     */
    public function get(ProductId $id): Product;

    public function getMany(ProductId ...$ids): ProductCollection;

    public function all(int $page, int $perPage): ProductCollection;

    public function save(Product $product): void;

    public function findByName(string $name): ?Product;

    public function delete(Product $product): void;
}
