<?php

declare(strict_types=1);

namespace Acme\Infrastructure\Product;

use Acme\Domain\Product\ProductCollection;
use Acme\Domain\Product\ProductNotFoundException;
use Acme\Domain\Product\InvalidProductException;
use Acme\Domain\Product\Product;
use Acme\Domain\Product\ProductId;
use Acme\Domain\Product\ProductName;
use Acme\Domain\Product\ProductPrice;
use Acme\Domain\Product\ProductRepository;
use PDO;
use WonderNetwork\SlimKernel\Accessor\ArrayAccessor;
use function WonderNetwork\SlimKernel\Collection\map;

final readonly class PdoProductRepository implements ProductRepository {
    public function __construct(private PDO $pdo) {
    }

    public function get(ProductId $id): Product {
        $sql = $this->pdo->prepare(
            <<<SQL
            SELECT * FROM product WHERE id = :id
            SQL,
        );
        $sql->execute([
            ':id' => $id->value,
        ]);

        $data = $sql->fetch(PDO::FETCH_ASSOC) ?? throw new ProductNotFoundException();
        return $this->hydrateOne($data);
    }

    public function all(int $page, int $perPage): ProductCollection {
        $page = max(1, $page);
        $perPage = min(10, $perPage);
        $skip = ($page - 1) * $perPage;

        // limit based pagination ain’t the greatest, but…
        $products = $this->pdo->prepare("SELECT * FROM product LIMIT :skip, :perPage");
        $products->execute([':skip' => $skip, ':perPage' => $perPage]);

        return new ProductCollection(
            ...
            map(
                $products->fetchAll(PDO::FETCH_ASSOC),
                $this->hydrateOne(...),
            ),
        );
    }


    public function getMany(ProductId ...$ids): ProductCollection {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $products = $this->pdo->prepare("SELECT * FROM product WHERE id in ($placeholders)");
        $products->execute(
            map($ids, static fn (ProductId $id) => $id->value),
        );

        return new ProductCollection(
            ...
            map(
                $products->fetchAll(PDO::FETCH_ASSOC),
                $this->hydrateOne(...),
            ),
        );
    }

    public function save(Product $product): void {
        // sqlite has a different upsert syntax
        $this->pdo
            ->prepare(
                <<<SQL
                INSERT INTO product (id, name, price) 
                VALUES (:id, :name, :price)
                ON CONFLICT DO UPDATE SET name = :name, price = :price
                SQL,
            )
            ->execute([
                ':id' => $product->id->value,
                ':name' => $product->name->value,
                ':price' => $product->price->value,
            ]);
    }

    public function findByName(string $name): ?Product {
        $sql = $this->pdo->prepare(
            <<<SQL
            SELECT * FROM product WHERE name = :name
            SQL,
        );
        $sql->execute([
            ':name' => $name,
        ]);

        $data = $sql->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->hydrateOne($data) : null;
    }

    public function delete(Product $product): void {
        $this->pdo
            ->prepare("DELETE FROM product WHERE id = :id")
            ->execute([':id' => $product->id->value]);
    }

    /**
     * Doctrine should do the heavy lifting, but the entities are small
     * enough so that this isn’t a chore. My LLM fills it almost entirely
     *
     * @throws InvalidProductException
     */
    private function hydrateOne(mixed $row): Product {
        $accessor = ArrayAccessor::of($row);
        return new Product(
            id: ProductId::of($accessor->string('id')),
            name: ProductName::of($accessor->string('name')),
            price: ProductPrice::of($accessor->int('price')),
        );
    }
}
