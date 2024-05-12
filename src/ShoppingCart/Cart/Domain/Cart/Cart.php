<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

final class Cart
{
    public function __construct(
        private readonly CartId $id,
        private readonly Items $items,
        private readonly bool $confirmed = false,
    ) {}

    public function id(): CartId
    {
        return $this->id;
    }

    public function items(): Items
    {
        return $this->items;
    }

    public function confirmed(): bool
    {
        return $this->confirmed;
    }
}