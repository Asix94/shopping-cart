<?php

namespace App\Tests\Shared;

use App\ShoppingCart\Cart\Domain\Cart\Item;
use App\ShoppingCart\Cart\Domain\Cart\Quantity;
use App\ShoppingCart\Product\Domain\Product;

final class ItemMother
{
    public static function create(Product $product): Item
    {
        return new Item(
            $product,
            Quantity::fromInt(1)
        );
    }
}