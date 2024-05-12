<?php

namespace App\ShoppingCart\Cart\Application\Cart;

use App\ShoppingCart\Cart\Domain\Cart\Cart;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddCart\AddCartRequest;

final class CartCreator
{
    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(AddCartRequest $cartRequest): void
    {
        $cart = new Cart(
            CartId::fromString($cartRequest->id()),
            Items::create([])
        );
        $this->cartRepository->save($cart);
    }
}