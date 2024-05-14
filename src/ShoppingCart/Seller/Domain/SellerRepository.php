<?php

namespace App\ShoppingCart\Seller\Domain;

interface SellerRepository
{
    public function save(Seller $seller): void;
    public function remove(SellerId $id): void;
    public function findById(SellerId $id): ?Seller;
}