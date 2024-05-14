<?php

namespace App\Tests\Unit\ShoppingCart\Seller\Application\Mothers;

use App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\AddSeller\AddSellerRequest;

final class AddRequestMother
{
    public static function createRequest(string $id, string $name): AddSellerRequest
    {
        return AddSellerRequest::sellerRequest(
            $id,
            $name
        );
    }
}