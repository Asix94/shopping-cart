<?php

namespace App\ShoppingCart\Product\Domain;

use App\ShoppingCart\Shared\Domain\Decimal;

final class Price extends Decimal
{
    protected function validate(): void
    {
        $this->validator()->positive();
    }
}