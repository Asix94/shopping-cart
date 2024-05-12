<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddItem;

final class AddItemRequest
{
    public function __construct(
        private readonly string $cartId,
        private readonly string $productId,
        private readonly int $quantity,
    ) {}

    public static function itemRequest(string $cartId, string $productId, int $quantity): self
    {
        return new self($cartId, $productId, $quantity);
    }
    public function cartId(): string
    {
        return $this->cartId;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}