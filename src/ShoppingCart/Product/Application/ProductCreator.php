<?php

namespace App\ShoppingCart\Product\Application;

use App\ShoppingCart\Product\Domain\Name;
use App\ShoppingCart\Product\Domain\Price;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\ProductRepository;
use App\ShoppingCart\Product\Domain\SellerId;
use App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\AddProduct\AddProductRequest;

final class ProductCreator
{
    public function __construct(private readonly ProductRepository $productRepository) {}

    public function __invoke(AddProductRequest $productRequest): void
    {
        $product = new Product(
            ProductId::fromString($productRequest->id()),
            SellerId::fromString($productRequest->sellerId()),
            Name::fromString($productRequest->name()),
            Price::fromFloat($productRequest->price()),
        );
        $this->productRepository->save($product);
    }
}