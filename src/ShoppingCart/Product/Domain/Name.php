<?php

namespace App\ShoppingCart\Product\Domain;

use App\ShoppingCart\Shared\Domain\Text;

final class Name extends Text
{
    protected function validate(): void
    {
        $this->validator()->notBlank();
    }
}