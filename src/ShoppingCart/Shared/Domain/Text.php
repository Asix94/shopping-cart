<?php

namespace App\ShoppingCart\Shared\Domain;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;
abstract class Text implements JsonSerializable, Stringable
{
    final private function __construct(private string $value)
    {
        $this->validate();
    }

    /**
     * @throws InvalidArgumentException
     */
    abstract protected function validate(): void;

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public static function tryFromString(?string $value): ?static
    {
        if (null === $value) {
            return null;
        }

        return static::fromString($value);
    }

    /**
     * @deprecated use normalize method to normalize value on create
     */
    public static function tryTrimmedFromString(?string $value): ?static
    {
        if (null === $value) {
            return null;
        }

        return static::fromString(trim($value));
    }

    public static function fromInt(int $value): static
    {
        return new static((string)$value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    protected function validator(): TextValidator
    {
        return new TextValidator($this);
    }

    public function equals(self $other): bool
    {
        return static::class === $other::class
               && $this->value === $other->value;
    }

    public function length(): int
    {
        return strlen($this->value);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}