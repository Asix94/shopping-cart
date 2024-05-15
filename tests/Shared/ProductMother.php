<?php

namespace App\Tests\Shared;

use App\ShoppingCart\Product\Domain\Name;
use App\ShoppingCart\Product\Domain\Price;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\SellerId;
use Ramsey\Uuid\Uuid;

final class ProductMother
{
    public static function create(SellerId $sellerId): Product
    {
        return new Product(
            ProductId::fromString(Uuid::uuid4()->toString()),
            $sellerId,
            Name::fromString('test'),
            Price::fromFloat(20),
        );
    }
}