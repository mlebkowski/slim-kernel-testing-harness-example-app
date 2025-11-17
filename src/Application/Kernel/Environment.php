<?php

declare(strict_types=1);

namespace Acme\Application\Kernel;

enum Environment: string {
    case Production = 'prod';
    case Test = 'test';
    case Development = 'dev';

    public function displayErrors(): bool {
        return match ($this) {
            self::Production => false,
            self::Test, self::Development => true,
        };
    }
}
