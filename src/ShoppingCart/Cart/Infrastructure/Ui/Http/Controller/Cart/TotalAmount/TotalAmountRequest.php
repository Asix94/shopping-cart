<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\TotalAmount;

final class TotalAmountRequest
{
    public function __construct(private readonly string $cartId) {}

    public static function totalAmountRequest(string $cartId): TotalAmountRequest
    {
        return new self($cartId);
    }

    public function cartId(): string
    {
        return $this->cartId;
    }
}