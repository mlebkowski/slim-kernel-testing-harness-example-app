<?php

declare(strict_types=1);

namespace Acme;

use Acme\Application\Authorization\Role;

final class ProductApiTest extends Application\ApplicationTestCase {
    public function testCanAddProduct(): void {
        $this->productUseCase(Role::Admin)
            ->add(name: 'Fallout 2', price: 2.99);

        $this->productUseCase(Role::Admin)
            ->list(perPage: 10)
            ->named('Fallout 2')
            ->assertExists()
            ->first()
            ->assertPrice(2.99);
    }

    public function testCanRemoveProduct(): void {
        $this->productUseCase(Role::Admin)
            ->add(name: 'Fallout 2', price: 2.99);

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

    public function testNameIsUnique(): void {
        $this->productUseCase(Role::Admin)
            ->expectFailure()
            // that’s in the fixtures!
            ->add(name: 'Fallout', price: 2.99)
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
            ->add(name: 'Fallout 2', price: 2.99)
            ->assertForbidden();
    }
}
