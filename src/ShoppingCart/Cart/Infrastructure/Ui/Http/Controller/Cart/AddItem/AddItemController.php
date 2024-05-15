<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddItem;

use App\ShoppingCart\Cart\Application\Item\ItemCreator;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedItemIsInCartException;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedSaveItemCartException;
use App\ShoppingCart\Product\Domain\Exceptions\FailedFindProductException;
use App\ShoppingCart\Shared\Domain\Exception\ValidationException;
use App\ShoppingCart\Shared\Domain\ValidateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class AddItemController
{
    public function __construct(private readonly ItemCreator $creator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            ValidateRequest::validate($request, ['cart_id', 'product_id']);

            $itemRequest = AddItemRequest::itemRequest(
                $request->query->get('cart_id'),
                $request->query->get('product_id')
            );

            $this->creator->__invoke($itemRequest);
            return new JsonResponse('Item is saved successfully', 201);
        } catch (FailedSaveItemCartException | FailedItemIsInCartException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (FailedFindProductException $e) {
            return new JsonResponse($e->getMessage(), 404);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}