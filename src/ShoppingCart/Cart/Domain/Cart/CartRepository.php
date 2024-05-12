<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

interface CartRepository
{
    public function save(Cart $cart): void;

    public function cartConfirmed(CartId $cartId): void;
}