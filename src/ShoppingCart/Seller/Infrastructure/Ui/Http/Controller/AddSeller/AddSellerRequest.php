<?php

namespace App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\AddSeller;
final class AddSellerRequest
{
    public function __construct(private readonly string $id, private readonly string $name) {}
    public static function sellerRequest(string $id, string $name): self
    {
        return new self($id, $name);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}