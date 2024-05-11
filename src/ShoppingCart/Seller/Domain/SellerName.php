<?php

namespace App\ShoppingCart\Seller\Domain;

use App\ShoppingCart\Shared\Domain\Text;

final class SellerName extends Text
{
    protected function validate(): void
    {
        $this->validator()->notBlank();
    }
}