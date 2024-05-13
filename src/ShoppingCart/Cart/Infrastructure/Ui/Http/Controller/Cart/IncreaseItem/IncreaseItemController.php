<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\IncreaseItem;

use App\ShoppingCart\Cart\Application\Item\ItemIncrementor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class IncreaseItemController
{
    public function __construct(private readonly ItemIncrementor $icrementor) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $cartId = $request->query->get('cart_id');
            $productId = $request->query->get('product_id');

            $increaseItemRequest = IncreaseItemRequest::increaseItemRequest($cartId, $productId);
            $this->icrementor->__invoke($increaseItemRequest);
            return new JsonResponse('Item increase successfully', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}