<?php

namespace App\ShoppingCart\Shared\Domain;

use InvalidArgumentException;
final class DecimalValidator
{
    public function __construct(private Decimal $decimal)
    {
    }

    public function positive(): self
    {
        if ($this->decimal->toFloat() < 0) {
            throw new InvalidArgumentException($this->decimal::class.' must be positive.');
        }

        return $this;
    }

    public function between(float $min, float $max): self
    {
        if ($this->decimal->toFloat() < $min || $this->decimal->toFloat() > $max) {
            throw new InvalidArgumentException(
                sprintf('%s must be between %s and %s', $this->decimal::class, $min, $max)
            );
        }

        return $this;
    }
}