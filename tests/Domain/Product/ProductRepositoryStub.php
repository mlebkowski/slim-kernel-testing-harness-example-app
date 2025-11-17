<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

use RuntimeException;
use function WonderNetwork\SlimKernel\Collection\collection;
use function WonderNetwork\SlimKernel\Collection\filter;

/**
 * This is why we do interface segregation. This repo is already massive
 */
final readonly class ProductRepositoryStub implements ProductRepository {
    public static function givenProductsExists(Product ...$products): self {
        return new self($products);
    }

    /**
     * @param Product[] $products
     */
    private function __construct(private array $products) {
    }

    public function get(ProductId $id): Product {
        return collection($this->products)
            ->find(static fn (Product $product) => $id->equals($product->id))
            ?? throw new ProductNotFoundException();
    }

    public function getMany(ProductId ...$ids): ProductCollection {
        $matchesId = static fn (Product $product) => collection($ids)
            ->some(static fn (ProductId $id) => $id->equals($product->id));

        return new ProductCollection(
            ...
            filter($this->products, $matchesId),
        );
    }

    public function all(int $page, int $perPage): ProductCollection {
        return new ProductCollection(
            ...
            array_slice($this->products, ($page - 1) * $perPage, $perPage)
        );
    }

    public function save(Product $product): void {
        throw new RuntimeException('Not implemented');
    }

    public function findByName(string $name): ?Product {
        return collection($this->products)->find(
            static fn (Product $product) => $name === $product->name->value,
        );
    }

    public function delete(Product $product): void {
        throw new RuntimeException('Not implemented');
    }
}
