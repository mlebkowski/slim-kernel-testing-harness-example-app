<?php

declare(strict_types=1);

namespace Acme\Infrastructure\Cart;

use Acme\Domain\Cart\Cart;
use Acme\Domain\Cart\CartRepository;
use PDO;
use Throwable;

final readonly class PdoCartRepository implements CartRepository {
    public function __construct(private Pdo $pdo) {
    }

    public function save(Cart $cart): void {
        $this->pdo->beginTransaction();
        try {
            $this->pdo
                ->prepare('DELETE FROM cart_item WHERE cart_id = :id')
                ->execute([':id' => $cart->id->value]);
            $this->pdo
                ->prepare('DELETE FROM cart WHERE id = :id')
                ->execute([':id' => $cart->id->value]);

            $this->pdo
                ->prepare('INSERT INTO cart VALUES(:id, :userId)')
                ->execute([':id' => $cart->id->value, ':userId' => $cart->userId]);

            $itemSql = $this->pdo->prepare('INSERT INTO cart_item VALUES(:cartId, :productId, :quantity)');

            foreach ($cart->items as $item) {
                $itemSql->execute(
                    [
                        ':cartId' => $cart->id->value,
                        ':productId' => $item->productId->value,
                        ':quantity' => $item->quantity,
                    ],
                );
            }
            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
