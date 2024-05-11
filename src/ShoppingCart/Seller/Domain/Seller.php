<?php

namespace App\ShoppingCart\Seller\Domain;

final class Seller
{
    public function __construct(private readonly SellerId $id, private readonly SellerName $name) {}

    public function id(): SellerId
    {
        return $this->id;
    }

    public function name(): SellerName
    {
        return $this->name;
    }
}