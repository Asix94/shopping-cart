<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveAllItems;

final class RemoveAllItemRequest
{
    public function __construct(private readonly string $cartId) {}

    public static function removeAllItemRequest(string $cartId): RemoveAllItemRequest
    {
        return new self($cartId);
    }

    public function cartId(): string
    {
        return $this->cartId;
    }
}