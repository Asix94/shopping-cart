<?php

namespace App\ShoppingCart\Shared\Domain;

use InvalidArgumentException;

final class TextValidator
{
    public function __construct(private Text $text)
    {
    }

    public function notBlank(bool $trim = false): self
    {
        $value = $this->text->toString();

        if ($trim) {
            $value = trim($value);
        }
        if ($value === '') {
            throw new InvalidArgumentException($this->text::class . ' must not be blank.');
        }

        return $this;
    }

    public function maxLength(int $max): self
    {
        if (strlen($this->text->toString()) > $max) {
            throw new InvalidArgumentException(sprintf('%s max length is: %d.', $this->text::class, $max));
        }

        return $this;
    }

    public function minLength(int $min): self
    {
        if (strlen($this->text->toString()) < $min) {
            throw new InvalidArgumentException(sprintf('%s min length is: %d.', $this->text::class, $min));
        }

        return $this;
    }

    public function length(int $value): self
    {
        if ($this->text->length() !== $value) {
            throw new InvalidArgumentException(
                sprintf('%s must have %s characters', $this->text::class, $value)
            );
        }

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function regex(string $pattern): self
    {
        if (!preg_match($pattern, $this->text->toString())) {
            throw new InvalidArgumentException(
                sprintf('%s must match regex "%s" pattern.', $this->text::class, $pattern)
            );
        }

        return $this;
    }
}