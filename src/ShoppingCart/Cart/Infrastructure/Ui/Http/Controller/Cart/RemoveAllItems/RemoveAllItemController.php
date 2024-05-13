<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveAllItems;

use App\ShoppingCart\Cart\Application\Item\ItemsEliminator;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedRemoveAllItemCartException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class RemoveAllItemController
{
    public function __construct(private readonly ItemsEliminator $eliminator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $cartId = $request->query->get('cart_id');

            $itemRequest = RemoveAllItemRequest::removeAllItemRequest($cartId);
            $this->eliminator->__invoke($itemRequest);

            return new JsonResponse('Item is remove successfully', 201);
        } catch (FailedRemoveAllItemCartException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}