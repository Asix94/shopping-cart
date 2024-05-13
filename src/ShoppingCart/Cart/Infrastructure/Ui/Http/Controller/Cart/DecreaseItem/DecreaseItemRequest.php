<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\DecreaseItem;

final class DecreaseItemRequest
{
    public function __construct(private readonly string $cartId, private readonly string $productId) {}

    public static function decreaseItemRequest(string $cartId, string $productId): DecreaseItemRequest
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