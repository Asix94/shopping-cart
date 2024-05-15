<?php

namespace App\ShoppingCart\Cart\Application\Item;

use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveItem\RemoveItemRequest;
use App\ShoppingCart\Product\Domain\ProductId;

class ItemEliminator
{
    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(RemoveItemRequest $itemRequest): void
    {
        $this->cartRepository->removeItemCart(
            CartId::fromString($itemRequest->cartId()),
            ProductId::fromString($itemRequest->productId())
        );
    }

}