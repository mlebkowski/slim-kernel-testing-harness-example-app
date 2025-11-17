<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

final readonly class ProductId {
    private const string PREFIX = 'prd_';

    public static function some(): self {
        return new self(self::PREFIX.bin2hex(random_bytes(16)));
    }

    public static function of(string $id): self {
        return new self($id);
    }

    public function __construct(public string $value) {
        if (false === str_starts_with($value, self::PREFIX)) {
            throw new InvalidProductException('Invalid product id: '.$value);
        }
    }
}
