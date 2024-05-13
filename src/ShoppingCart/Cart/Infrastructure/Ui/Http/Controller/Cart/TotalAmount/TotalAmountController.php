<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\TotalAmount;

use App\ShoppingCart\Cart\Application\Cart\CartFinder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class TotalAmountController
{
    public function __construct(private readonly CartFinder $finder) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $cartId = $request->query->get('cart_id');
            $this->ValidateParams($cartId, 'cart_id');

            $totalAmount = TotalAmountRequest::totalAmountRequest($cartId);
            $cart = $this->finder->__invoke($totalAmount);

            return new JsonResponse('Total amount is ' . $cart->totalAmount() . ' $');
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

    }

    private function ValidateParams(?string $param, string $nameParam): void
    {
        if (!$param) {
            throw new \Exception('Parameter ' . $nameParam . ' is required.');
        }
    }
}