<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

use App\ShoppingCart\Product\Domain\Product;

final class Item
{
    public function __construct(
        private readonly Product $product,
        private Quantity $quantity
    ) {}

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }

    public function increaseQuantity(): void
    {
        $quantity = $this->quantity->toInt() + 1;
        $this->quantity = Quantity::fromInt($quantity);
    }

    public function decreaseQuantity(): void
    {
        $quantity = $this->quantity->toInt() -1;
        $this->quantity = Quantity::fromInt($quantity);
    }
}