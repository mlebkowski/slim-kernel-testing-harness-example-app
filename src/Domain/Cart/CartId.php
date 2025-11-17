<?php

declare(strict_types=1);

namespace Acme\Domain\Cart;

final readonly class CartId {
    private const string PREFIX = 'crt_';

    public static function some(): self {
        return new self(self::PREFIX.bin2hex(random_bytes(16)));
    }

    /**
     * @throws InvalidCartException
     */
    public static function of(string $id): self {
        return new self($id);
    }

    public function __construct(public string $value) {
        if (false === str_starts_with($value, self::PREFIX)) {
            throw new InvalidCartException('Invalid cart id: '.$value);
        }
    }
}
