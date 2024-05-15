<?php

namespace App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\ConfirmedCart;

use App\ShoppingCart\Cart\Application\Cart\CartConfirmed;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedConfirmCartException;
use App\ShoppingCart\Shared\Domain\Exception\ValidationException;
use App\ShoppingCart\Shared\Domain\ValidateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class ConfirmedCartController
{
    public function __construct(private readonly CartConfirmed $confirmed) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            ValidateRequest::validate($request, ['cart_id']);
            $cartRequest = ConfirmedCartRequest::confirmedCartRequest(
                $request->query->get('cart_id')
            );
            $this->confirmed->__invoke($cartRequest);

            return new JsonResponse('Cart is confirmed successfully', 201);
        } catch (FailedConfirmCartException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}