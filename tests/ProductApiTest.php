<?php

declare(strict_types=1);

namespace Acme;

use Acme\Application\Authorization\Role;

final class ProductApiTest extends Application\ApplicationTestCase {
    public function testPagination(): void {
        $this->productUseCase(Role::Admin)
            ->list()
            ->expectCount(3);

        $this->productUseCase(Role::Admin)
            ->list(page: 2)
            ->expectCount(2);
    }

    public function testCanAddProduct(): void {
        $this->productUseCase(Role::Admin)
            ->add(name: 'Fallout 2', price: 299);

        $this->productUseCase(Role::Admin)
            ->list(perPage: 10)
            ->named('Fallout 2')
            ->assertExists()
            ->first()
            ->assertPrice(299);
    }

    public function testCanRemoveProduct(): void {
        $this->productUseCase(Role::Admin)
            ->add(name: 'Fallout 2', price: 299);

        $this->productUseCase(Role::Admin)
            ->list(perPage: 10)
            ->named('Fallout 2')
            ->first()
            ->remove();

        $this->productUseCase(Role::Admin)
            ->list(perPage: 10)
            ->named('Fallout 2')
            ->assertDoesNotExist();
    }

    public function testCanEditProduct(): void {
        $randomName = 'Diablo & Hellfire';
        $randomPrice = 50; // is it on sale?

        $productApi = $this->productUseCase(Role::Admin);

        $productId = $productApi
            ->list()
            ->first()
            ->assertNotNamed($randomName)
            ->id;

        $productApi->update(id: $productId, name: $randomName);
        $productApi->list()->first()->assertNamed($randomName);

        $productApi->update(id: $productId, price: $randomPrice);
        $productApi->list()->first()->assertPrice($randomPrice);
    }

    public function testNameIsUnique(): void {
        $this->productUseCase(Role::Admin)
            ->expectFailure()
            // that’s in the fixtures!
            ->add(name: 'Fallout', price: 299)
            ->slimErrorPage()
            ->assertMessage('Product already exists!');
    }

    public function testAdminIsRequiredToManageProducts(): void {
        $this->productUseCase(Role::None)
            ->expectFailure()
            ->list()
            ->assertForbidden();

        $this->productUseCase(Role::None)
            ->expectFailure()
            ->add(name: 'Fallout 2', price: 299)
            ->assertForbidden();
    }
}
