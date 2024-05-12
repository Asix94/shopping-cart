<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddItem;

use App\ShoppingCart\Cart\Application\Item\ItemCreator;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedSaveItemCartException;
use App\ShoppingCart\Product\Domain\Exceptions\FailedFindProductException;
use Doctrine\DBAL\Driver\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class AddItemController
{
    public function __construct(private readonly ItemCreator $creator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $cartId = $request->query->get('cart_id');
            $productId = $request->query->get('product_id');
            $quantity = $request->query->get('quantity');

            $this->ValidateParams($cartId, 'cart id');
            $this->ValidateParams($productId, 'product id');
            $this->ValidateParams($quantity, 'quantity');

            $itemRequest = AddItemRequest::itemRequest(
                $cartId,
                $productId,
                $quantity
            );

            $this->creator->__invoke($itemRequest);
            return new JsonResponse('Item is saved successfully', 201);
        } catch (FailedSaveItemCartException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (FailedFindProductException | \Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }

    private function ValidateParams(?string $param, string $nameParam): void
    {
        if (!$param) {
            throw new \Exception('Parameter ' . $nameParam . ' is required.');
        }
    }
}