<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveItem;

use App\ShoppingCart\Cart\Application\Item\ItemEliminator;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedRemoveItemCartException;
use App\ShoppingCart\Shared\Domain\Exception\ValidationException;
use App\ShoppingCart\Shared\Domain\ValidateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class RemoveItemController
{
    public function __construct(private readonly ItemEliminator $eliminator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            ValidateRequest::validate($request, ['cart_id', 'product_id']);
            $itemRequest = RemoveItemRequest::removeItemRequest(
                $request->query->get('cart_id'),
                $request->query->get('product_id')
            );
            $this->eliminator->__invoke($itemRequest);

            return new JsonResponse('Item is remove successfully', 201);
        } catch (FailedRemoveItemCartException $e) {
            return new JsonResponse($e->getMessage(), 500);
        }  catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}