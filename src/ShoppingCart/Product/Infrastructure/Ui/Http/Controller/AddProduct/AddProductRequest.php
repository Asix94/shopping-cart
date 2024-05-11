<?php

namespace App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\AddProduct;

final class AddProductRequest
{
    public function __construct(
        private readonly string $id,
        private readonly string $sellerId,
        private readonly string $name,
        private readonly float $price,
    ) {}

    public static function productRequest(string $id, string $sellerId, string $name, float $price): self
    {
        return new self($id, $sellerId, $name,$price);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function sellerId(): string
    {
        return $this->sellerId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }
}