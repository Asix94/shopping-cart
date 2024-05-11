<?php

namespace App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\RemoveSeller;

final class RemoveSellerRequest
{
    public function __construct(private readonly string $id) {}
    public static function sellerRequest(string $id): self
    {
        return new self($id);
    }

    public function id(): string
    {
        return $this->id;
    }
}