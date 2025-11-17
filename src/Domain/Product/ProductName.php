<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

final readonly class ProductName {
    /**
     * @throws InvalidProductException
     */
    public static function of(string $name): self {
        return new self($name);
    }

    /**
     * Just a dummy showcase to present how we guard domain invariants
     *
     * @throws InvalidProductException
     */
    public function __construct(
        public string $value,
    ) {
        if ("" === $value) {
            throw new InvalidProductException("Product name can't be empty");
        }
    }
}
