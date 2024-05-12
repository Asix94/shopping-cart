<?php

namespace App\ShoppingCart\Product\Domain;

interface ProductRepository
{
    public function save(Product $product): void;
    public function remove(ProductId $id): void;
    public function findById(ProductId $id): ?Product;
}