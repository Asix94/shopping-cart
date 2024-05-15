<?php

namespace App\Tests\Shared;

use App\ShoppingCart\Seller\Domain\Seller;
use App\ShoppingCart\Seller\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\SellerName;
use Ramsey\Uuid\Uuid;

final class SellerMother
{
    public static function create(): Seller
    {
        return new Seller(
            SellerId::fromString(Uuid::uuid4()->toString()),
            SellerName::fromString('test')
        );
    }
}