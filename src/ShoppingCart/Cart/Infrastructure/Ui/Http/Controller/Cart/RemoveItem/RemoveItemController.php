<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveItem;

use App\ShoppingCart\Cart\Application\Item\ItemEliminator;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedRemoveItemCartException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class RemoveItemController
{
    public function __construct(private readonly ItemEliminator $eliminator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $cartId = $request->query->get('cart_id');
            $productId = $request->query->get('product_id');

            $itemRequest = RemoveItemRequest::removeItemRequest($cartId, $productId);
            $this->eliminator->__invoke($itemRequest);

            return new JsonResponse('Item is remove successfully', 201);
        } catch (FailedRemoveItemCartException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}