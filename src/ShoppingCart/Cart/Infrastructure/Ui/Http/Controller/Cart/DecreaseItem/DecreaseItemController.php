<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\DecreaseItem;

use App\ShoppingCart\Cart\Application\Item\ItemDecrement;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedItemNotFoundException;
use App\ShoppingCart\Shared\Domain\Exception\ValidationException;
use App\ShoppingCart\Shared\Domain\ValidateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class DecreaseItemController
{
    public function __construct(private readonly ItemDecrement $decrementor) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            ValidateRequest::validate($request, ['cart_id','product_id']);
            $decreaseItemRequest = DecreaseItemRequest::decreaseItemRequest(
                $request->query->get('cart_id'),
                $request->query->get('product_id')
            );
            $this->decrementor->__invoke($decreaseItemRequest);
            return new JsonResponse('Item decrease successfully', 201);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), 400);
        } catch (FailedItemNotFoundException $e) {
            return new JsonResponse($e->getMessage(), 404);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }
}