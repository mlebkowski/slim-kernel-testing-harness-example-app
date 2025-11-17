<?php

declare(strict_types=1);

namespace Acme\User;

use Acme\Domain\User\User;

final readonly class UserMother {
    public static function some(): User {
        return new User(
            id: bin2hex(random_bytes(16)),
        );
    }
}
