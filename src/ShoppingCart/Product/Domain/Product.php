<?php

namespace App\ShoppingCart\Product\Domain;

final class Product
{
    public function __construct(
        private readonly ProductId $productId,
        private readonly SellerId $sellerId,
        private readonly Name $name,
        private readonly Price $price
    ) {}

    public function id(): ProductId
    {
        return $this->productId;
    }

    public function sellerId(): SellerId
    {
        return $this->sellerId;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function price(): Price
    {
        return $this->price;
    }
}