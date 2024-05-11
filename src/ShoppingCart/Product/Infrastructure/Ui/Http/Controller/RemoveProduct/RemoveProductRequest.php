<?php

namespace App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\RemoveProduct;

final class RemoveProductRequest
{
    public function __construct(
        private readonly string $id
    ) {}

    public static function productRequest(string $id): self
    {
        return new self($id);
    }

    public function id(): string
    {
        return $this->id;
    }
}