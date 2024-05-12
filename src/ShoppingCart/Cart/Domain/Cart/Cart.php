<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

final class Cart
{
    public function __construct(private readonly CartId $id) {}

    public function id(): CartId
    {
        return $this->id;
    }
}