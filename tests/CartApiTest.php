<?php

declare(strict_types=1);

namespace Acme;

use Acme\Application\ApplicationTestCase;
use Acme\Application\Authorization\Role;
use Acme\Domain\Product\ProductId;
use Acme\User\UserMother;

final class CartApiTest extends ApplicationTestCase {
    public function testCreateCart(): void {
        $this
            ->cartUseCase(UserMother::some())
            ->create();
    }

    public function testAddProductsCart(): void {
        $cartApi = $this->cartUseCase(UserMother::some());

        $cartId = $cartApi->create()->id;
        $productId = $this
            ->productUseCase(Role::None)
            ->list()
            ->first()
            ->id;

        $cartApi->addItem(cartId: $cartId, productId: $productId, quantity: 2);
        $cartApi
            ->get($cartId)
            ->assertHasTotalPrice()
            ->firstItem()
            ->assertProductId($productId)
            ->assertQuantity(2);
    }

    public function testCartProductLimit(): void {
        $cartApi = $this->cartUseCase(UserMother::some());
        $cartId = $cartApi->create()->id;

        $productIds = $this
            ->productUseCase(Role::None)
            ->list(perPage: 4)
            ->allIds();

        $last = array_pop($productIds);
        foreach ($productIds as $productId) {
            $cartApi->addItem(cartId: $cartId, productId: $productId);
        }

        $cartApi
            ->expectFailure()
            ->addItem(cartId: $cartId, productId: $last, quantity: 4);
    }

    public function testAddProductsToAnotherUsersCart(): void {
        $alpha = $this->cartUseCase(UserMother::some());
        $bravo = $this->cartUseCase(UserMother::some());

        $cartId = $alpha->create()->id;
        $bravo
            ->expectFailure()
            ->addItem(cartId: $cartId, productId: ProductId::some()->value, quantity: 2);
    }

    public function testRemoveProductsFromCart(): void {
        $cartApi = $this->cartUseCase(UserMother::some());

        $cartId = $cartApi->create()->id;
        $productId = $this
            ->productUseCase(Role::None)
            ->list()
            ->first()
            ->id;

        $cartApi->addItem(cartId: $cartId, productId: $productId, quantity: 2);
        $cartApi->removeItem(cartId: $cartId, productId: $productId, quantity: 1);
        $cartApi
            ->get($cartId)
            ->assertHasTotalPrice()
            ->firstItem()
            ->assertProductId($productId)
            ->assertQuantity(1);
    }


}
