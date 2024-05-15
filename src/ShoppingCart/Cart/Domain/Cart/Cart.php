<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

final class Cart
{
    public CONST QUANTITY_DEFAULT = 1;
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

    public function totalAmount(): float
    {
        $totalAmount = 0;

        foreach ($this->items as $item) {
            $totalAmount += $item->product()->price()->value() * $item->quantity()->value();
        }
        return $totalAmount;
    }
}