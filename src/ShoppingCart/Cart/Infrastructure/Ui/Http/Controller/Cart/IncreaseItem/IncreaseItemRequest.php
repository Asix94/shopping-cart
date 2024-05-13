<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\IncreaseItem;

final class IncreaseItemRequest
{
    public function __construct(private readonly string $cartId, private readonly string $productId) {}

    public static function increaseItemRequest(string $cartId, string $productId): IncreaseItemRequest
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