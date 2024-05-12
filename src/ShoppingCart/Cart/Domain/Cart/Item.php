<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

use App\ShoppingCart\Product\Domain\Product;

final class Item
{
    public function __construct(
        private readonly Product $product,
        private readonly Quantity $quantity
    ) {}

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }
}