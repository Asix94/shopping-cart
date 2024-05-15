<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveAllItems;

use App\ShoppingCart\Cart\Application\Item\ItemsEliminator;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedRemoveAllItemCartException;
use App\ShoppingCart\Shared\Domain\Exception\ValidationException;
use App\ShoppingCart\Shared\Domain\ValidateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class RemoveAllItemController
{
    public function __construct(private readonly ItemsEliminator $eliminator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            ValidateRequest::validate($request, ['cart_id']);
            $itemRequest = RemoveAllItemRequest::removeAllItemRequest(
                $request->query->get('cart_id')
            );
            $this->eliminator->__invoke($itemRequest);

            return new JsonResponse('Item is remove successfully', 201);
        } catch (FailedRemoveAllItemCartException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}