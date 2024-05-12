<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

final class Cart
{
    public function __construct(
        private readonly CartId $id,
        private readonly bool $confirmed = true,
    ) {}

    public function id(): CartId
    {
        return $this->id;
    }

    public function confirmed(): bool
    {
        return $this->confirmed;
    }
}