<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddCart;

use App\ShoppingCart\Cart\Application\Cart\CartCreator;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedSaveCartException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AddCartController
{
    public function __construct(private readonly CartCreator $creator) {}

    public function __invoke(): JsonResponse
    {
        try {
            $id = Uuid::uuid4()->toString();
            $cartRequest = AddCartRequest::addCartRequest($id);
            $this->creator->__invoke($cartRequest);

            return new JsonResponse('Cart is saved successfully', 201);
        } catch (FailedSaveCartException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}