<?php

declare(strict_types=1);

namespace Acme;

use Acme\Application\ApplicationTestCase;
use Acme\User\UserMother;

final class CartApiTest extends ApplicationTestCase {
    public function testCreateCart(): void {
        $this
            ->cartUseCase(UserMother::some())
            ->create();
    }
}
