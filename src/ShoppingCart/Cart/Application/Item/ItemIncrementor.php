<?php

namespace App\ShoppingCart\Cart\Application\Item;

use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedItemNotFoundException;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\IncreaseItem\IncreaseItemRequest;
use App\ShoppingCart\Product\Domain\ProductId;

class ItemIncrementor
{
    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(IncreaseItemRequest $request): void
    {
        $item = $this->cartRepository->findItemByCartIdAndProductId(
            CartId::fromString($request->cartId()),
            ProductId::fromString($request->productId()),
        );

        if (null === $item) { throw new FailedItemNotFoundException(); }

        $item->increaseQuantity();

        $this->cartRepository->updateQuantity(CartId::fromString($request->cartId()), $item);
    }
}