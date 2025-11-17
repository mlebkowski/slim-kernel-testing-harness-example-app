<?php

declare(strict_types=1);

namespace Acme\Domain\Product;

final readonly class ProductPrice {
    const int PRECISION = 100;

    public static function ofInteger(int $price): self {
        return self::of($price / self::PRECISION);
    }

    /**
     * @throws InvalidProductException
     */
    public static function of(float $price): self {
        return new self($price);
    }

    /**
     * @throws InvalidProductException
     */
    public function __construct(public float $value) {
        $value *= self::PRECISION;

        if ((float) (int) $value !== $value) {
            // my current project has this spaceless concatenation in its code style 🤦‍♀️
            throw new InvalidProductException('Price has to high precision: '.($value / self::PRECISION));
        }
    }

    public function toInteger(): int {
        return (int) ($this->value * self::PRECISION);
    }
}
