<?php

namespace App\ShoppingCart\Product\Application;

use App\ShoppingCart\Product\Domain\Name;
use App\ShoppingCart\Product\Domain\Price;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\ProductRepository;
use App\ShoppingCart\Product\Domain\SellerId;
use App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\AddProduct\AddProductRequest;
use App\ShoppingCart\Seller\Domain\Exceptions\SellerNotFoundException;
use App\ShoppingCart\Seller\Domain\SellerRepository;

class ProductCreator
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly SellerRepository $sellerRepository
    ) {}

    public function __invoke(AddProductRequest $productRequest): void
    {
        // TODO CONVERT seller id to shared class
        $seller = $this->sellerRepository->findById(\App\ShoppingCart\Seller\Domain\SellerId::fromString($productRequest->sellerId()));
        if (null === $seller) { throw new SellerNotFoundException('Seller not found'); }

        $product = new Product(
            ProductId::fromString($productRequest->id()),
            SellerId::fromString($productRequest->sellerId()),
            Name::fromString($productRequest->name()),
            Price::fromFloat($productRequest->price()),
        );
        $this->productRepository->save($product);
    }
}