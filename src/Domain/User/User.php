<?php

declare(strict_types=1);

namespace Acme\Domain\User;

final readonly class User {
    public function __construct(
        public string $id,
    ) {
    }
}
