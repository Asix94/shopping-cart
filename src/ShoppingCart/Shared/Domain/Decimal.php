<?php

namespace App\ShoppingCart\Shared\Domain;

abstract class Decimal  implements \JsonSerializable
{
    private final function __construct(private readonly float $value)
    {
        $this->validate();
    }

    abstract protected function validate(): void;

    public static function zero(): static
    {
        return new static(0);
    }

    public static function fromFloat(float $value): static
    {
        return new static($value);
    }

    public function toFloat(): float
    {
        return $this->value;
    }

    public static function fromString(string $value): static
    {
        return new static((float) $value);
    }

    public function toString(): string
    {
        return (string) $this->value;
    }

    protected function validator(): DecimalValidator
    {
        return new DecimalValidator($this);
    }

    public function equals(self $other): bool
    {
        return $other::class === static::class
               && $other->value === $this->value;
    }

    public function jsonSerialize(): float
    {
        return $this->value;
    }

    public function isGreaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function value(): float
    {
        return $this->value;
    }
}