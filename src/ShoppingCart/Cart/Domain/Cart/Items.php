<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

use App\ShoppingCart\Shared\Domain\Collection;

class Items extends Collection
{
    public static function create(array $items): Items
    {
        return new self($items);
    }
    protected function type(): string
    {
        return Item::class;
    }
}