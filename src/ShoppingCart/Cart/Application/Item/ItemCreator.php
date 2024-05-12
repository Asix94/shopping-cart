<?php

namespace App\ShoppingCart\Cart\Application\Item;

use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Item;
use App\ShoppingCart\Cart\Domain\Cart\Quantity;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddItem\AddItemRequest;
use App\ShoppingCart\Product\Domain\Exceptions\FailedFindProductException;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\ProductRepository;

final class ItemCreator
{
    public function __construct(
        private readonly CartRepository $cartRepository,
        private readonly ProductRepository $productRepository,
    ) {}

    public function __invoke(AddItemRequest $itemRequest): void
    {
        $product = $this->productRepository->findById(ProductId::fromString($itemRequest->productId()));
        if ($product === null) { throw new FailedFindProductException('Product not found'); }

        $item = new Item(
            $product,
            Quantity::fromInt($itemRequest->quantity()),
        );
        $this->cartRepository->saveItemCart(CartId::fromString($itemRequest->cartId()), $item);
    }
}