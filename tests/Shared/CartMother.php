<?php

namespace App\Tests\Shared;

use App\ShoppingCart\Cart\Domain\Cart\Cart;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use Ramsey\Uuid\Uuid;

final class CartMother
{
    public static function create(Items $items): Cart
    {
        return new Cart(
            CartId::fromString(Uuid::uuid4()->toString()),
            $items
        );
    }
}