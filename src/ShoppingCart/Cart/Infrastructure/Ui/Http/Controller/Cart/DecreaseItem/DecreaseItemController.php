<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\DecreaseItem;

use App\ShoppingCart\Cart\Application\Item\ItemDecrement;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class DecreaseItemController
{
    public function __construct(private readonly ItemDecrement $decrementor) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $cartId = $request->query->get('cart_id');
            $productId = $request->query->get('product_id');

            $decreaseItemRequest = DecreaseItemRequest::decreaseItemRequest($cartId, $productId);
            $this->decrementor->__invoke($decreaseItemRequest);
            return new JsonResponse('Item decrease successfully', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}