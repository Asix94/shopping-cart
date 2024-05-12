<?php

namespace App\ShoppingCart\Shared\Domain;

use InvalidArgumentException;
final class IntegerValidator
{
    public function __construct(private Integer $integer)
    {
    }

    public function positive(): self
    {
        if ($this->integer->toInt() < 0) {
            throw new InvalidArgumentException($this->integer::class.' must be positive.');
        }

        return $this;
    }

    public function between(int $min, int $max): self
    {
        if ($this->integer->toInt() < $min || $this->integer->toInt() > $max) {
            throw new InvalidArgumentException(
                sprintf('%s value must be between %s and %s.', $this->integer::class, $min, $max)
            );
        }

        return $this;
    }
}