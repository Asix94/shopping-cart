<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\ConfirmedCart;

final class ConfirmedCartRequest
{
    public function __construct(private readonly string $id) {}

    public static function confirmedCartRequest(string $id): ConfirmedCartRequest
    {
        return new self($id);
    }
    public function id(): string
    {
        return $this->id;
    }
}