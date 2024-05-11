<?php

namespace App\ShoppingCart\Shared\Domain;

use InvalidArgumentException;
use function preg_match;
abstract class Uuid implements \JsonSerializable
{
    private const UUID_PATTERN = '/^[0-9a-f]{8}-(?:[0-9a-f]{4}-){3}[0-9a-f]{12}$/i';

    final private function __construct(private readonly string $value)
    {
        if (!preg_match(self::UUID_PATTERN, $this->value)) {
            throw new InvalidArgumentException(
                sprintf('"%s" is not a valid UUID.', $this->value)
            );
        }
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public static function tryFromString(?string $value): ?static
    {
        if (null === $value) {
            return null;
        }

        return self::fromString($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $value): bool
    {
        return $this->value === $value->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}