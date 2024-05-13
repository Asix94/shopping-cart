<?php

namespace App\ShoppingCart\Cart\Application\Item;

use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\DecreaseItem\DecreaseItemRequest;
use App\ShoppingCart\Product\Domain\ProductId;

final class ItemDecrement
{
    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(DecreaseItemRequest $request): void
    {
        $item = $this->cartRepository->findItemByCartIdAndProductId(
            CartId::fromString($request->cartId()),
            ProductId::fromString($request->productId()),
        );

        $item->decreaseQuantity();

        if ($item->quantity()->toInt() <= 0) {
            $this->cartRepository->removeItemCart(
                CartId::fromString($request->cartId()),
                ProductId::fromString($request->productId()),
            );
        } else {
            $this->cartRepository->updateQuantity(CartId::fromString($request->cartId()), $item);
        }
    }
}