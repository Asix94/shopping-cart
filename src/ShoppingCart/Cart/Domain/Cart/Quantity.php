<?php

namespace App\ShoppingCart\Cart\Domain\Cart;

use App\ShoppingCart\Shared\Domain\Integer;

final class Quantity extends Integer
{
    protected function validate(): void
    {
        $this->validator()->positive();
    }
}