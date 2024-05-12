<?php

namespace App\ShoppingCart\Cart\Application\Cart;

use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\ConfirmedCart\ConfirmedCartRequest;

final class CartConfirmed
{
    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(ConfirmedCartRequest $cartRequest): void
    {
        $cartId = CartId::fromString($cartRequest->id());
        $this->cartRepository->cartConfirmed($cartId);
    }
}