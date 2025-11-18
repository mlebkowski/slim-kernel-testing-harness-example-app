<?php

declare(strict_types=1);

namespace Acme\Infrastructure\Cart;

use Acme\Domain\Cart\Cart;
use Acme\Domain\Cart\CartId;
use Acme\Domain\Cart\CartItem;
use Acme\Domain\Cart\CartNotFoundException;
use Acme\Domain\Cart\CartRepository;
use Acme\Domain\Product\ProductId;
use PDO;
use Throwable;
use WonderNetwork\SlimKernel\Accessor\ArrayAccessor;
use function WonderNetwork\SlimKernel\Collection\map;

final readonly class PdoCartRepository implements CartRepository {
    public function __construct(private Pdo $pdo) {
    }

    public function get(CartId $id): Cart {
        $cartSql = $this->pdo->prepare('SELECT * FROM cart WHERE id = :id');
        $cartSql->execute([':id' => $id->value]);

        $cartData = $cartSql->fetch(PDO::FETCH_ASSOC)
            ?: throw new CartNotFoundException();
        $cartDataAccessor = ArrayAccessor::of($cartData);

        $itemSql = $this->pdo->prepare('SELECT * FROM cart_item WHERE cart_id = :id');
        $itemSql->execute([':id' => $id->value]);
        $itemData = map(
            $itemSql->fetchAll(PDO::FETCH_ASSOC),
            static fn (mixed $itemData) => ArrayAccessor::of($itemData),
        );

        return new Cart(
            id: CartId::of($cartDataAccessor->requireString('id')),
            userId: $cartDataAccessor->requireString('user_id'),
            items: map(
                $itemData,
                static fn (ArrayAccessor $data) => new CartItem(
                    productId: ProductId::of($data->requireString('product_id')),
                    quantity: $data->requireInt('quantity'),
                ),
            ),
        );
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
