<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\IncreaseItem;

use App\ShoppingCart\Cart\Application\Item\ItemIncrementor;
use App\ShoppingCart\Shared\Domain\Exception\ValidationException;
use App\ShoppingCart\Shared\Domain\ValidateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class IncreaseItemController
{
    public function __construct(private readonly ItemIncrementor $icrementor) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            ValidateRequest::validate($request, ['cart_id','product_id']);
            $increaseItemRequest = IncreaseItemRequest::increaseItemRequest(
                $request->query->get('cart_id'),
                $request->query->get('product_id')
            );
            $this->icrementor->__invoke($increaseItemRequest);
            return new JsonResponse('Item increase successfully', 201);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}