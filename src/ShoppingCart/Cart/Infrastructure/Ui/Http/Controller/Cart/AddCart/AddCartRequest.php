<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddCart;

final class AddCartRequest
{
    public function __construct(private readonly string $id) {}

    public static function addCartRequest(string $id): AddCartRequest
    {
        return new self($id);
    }
    public function id(): string
    {
        return $this->id;
    }
}