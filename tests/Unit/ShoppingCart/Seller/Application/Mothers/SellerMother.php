<?php

namespace App\Tests\Unit\ShoppingCart\Seller\Application\Mothers;

use App\ShoppingCart\Seller\Domain\Seller;
use App\ShoppingCart\Seller\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\SellerName;
use App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\AddSeller\AddSellerRequest;

final class SellerMother
{
    public static function createSeller(AddSellerRequest $request): Seller
    {
        return new Seller(
            SellerId::fromString($request->id()),
            SellerName::fromString($request->name()),
        );
    }
}