<?php

namespace App\ShoppingCart\Seller\Domain;

interface SellerRepository
{
    public function save(Seller $seller): void;
}