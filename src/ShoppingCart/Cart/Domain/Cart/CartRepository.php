<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

interface CartRepository
{
    public function save(Cart $cart): void;
}