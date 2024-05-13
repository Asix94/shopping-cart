<?php

namespace App\ShoppingCart\Shared\Domain;

abstract class Integer implements \JsonSerializable
{
    final private function __construct(private readonly int $value)
    {
        $this->validate();
    }

    public static function zero(): static
    {
        return new static(0);
    }

    abstract protected function validate(): void;

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public static function tryFromInt(?int $value): ?static
    {
        if (null === $value) {
            return null;
        }

        return self::fromInt($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return static::class === $other::class
               && $this->value === $other->value;
    }

    protected function validator(): IntegerValidator
    {
        return new IntegerValidator($this);
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }

    public function greaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function value(): int
    {
        return $this->value;
    }
}