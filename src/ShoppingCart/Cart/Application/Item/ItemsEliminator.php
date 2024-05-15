<?php

namespace App\ShoppingCart\Cart\Application\Item;

use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveAllItems\RemoveAllItemRequest;

class ItemsEliminator
{
    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(RemoveAllItemRequest $itemsRequest): void
    {
        $this->cartRepository->removeAllItemCart(
            CartId::fromString($itemsRequest->cartId())
        );
    }
}