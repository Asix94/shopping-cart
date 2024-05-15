<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddItem;

final class AddItemRequest
{
    public function __construct(
        private readonly string $cartId,
        private readonly string $productId
    ) {}

    public static function itemRequest(string $cartId, string $productId): self
    {
        return new self($cartId, $productId);
    }
    public function cartId(): string
    {
        return $this->cartId;
    }

    public function productId(): string
    {
        return $this->productId;
    }
}