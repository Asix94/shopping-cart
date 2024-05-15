<?php

namespace App\ShoppingCart\Cart\Application\Cart;

use App\ShoppingCart\Cart\Domain\Cart\Cart;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\TotalAmount\TotalAmountRequest;

class CartFinder
{
    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(TotalAmountRequest $request): Cart
    {
        return $this->cartRepository->findById(CartId::fromString($request->cartId()));
    }
}