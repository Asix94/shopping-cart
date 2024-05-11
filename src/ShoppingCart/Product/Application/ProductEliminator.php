<?php

namespace App\ShoppingCart\Product\Application;

use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\ProductRepository;
use App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\RemoveProduct\RemoveProductRequest;

final class ProductEliminator
{
    public function __construct(private readonly ProductRepository $productRepository) {}

    public function __invoke(RemoveProductRequest $productRequest): void
    {
        $productId = ProductId::fromString($productRequest->id());
        $this->productRepository->remove($productId);
    }
}