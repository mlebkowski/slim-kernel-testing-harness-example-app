<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

final readonly class ProductPrice {
    public static function zero(): self {
        return new self(0);
    }

    public static function of(int $price): self {
        return new self($price);
    }

    private function __construct(public int $value) {
    }

    /**
     * Operating on integers just to be triple sure
     */
    public function add(self $other): self {
        return new self($this->value + $other->value);
    }

    public function mul(int $quantity): self {
        return new self($this->value * $quantity);
    }
}
