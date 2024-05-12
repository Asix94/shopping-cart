<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveItem;

final class RemoveItemRequest
{
    public function __construct(private readonly string $cartId, private readonly string $productId) {}

    public static function removeItemRequest(string $cartId, string $productId): RemoveItemRequest
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