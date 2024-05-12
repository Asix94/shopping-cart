<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

use App\ShoppingCart\Product\Domain\ProductId;

interface CartRepository
{
    public function save(Cart $cart): void;

    public function cartConfirmed(CartId $cartId): void;

    public function saveItemCart(CartId $cartId, Item $item): void;

    public function removeItemCart(CartId $cartId, ProductId $productId): void;
}