<?php

namespace App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\RemoveSeller;

use App\ShoppingCart\Seller\Application\SellerEliminator;
use App\ShoppingCart\Seller\Domain\Exceptions\FailedRemoveSellerException;
use App\ShoppingCart\Shared\Domain\ValidateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class RemoveSellerController
{
    public function __construct(private readonly SellerEliminator $eliminator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            ValidateRequest::validate($request, ['id']);
            $sellerId = RemoveSellerRequest::sellerRequest(
                $request->get('id')
            );
            $this->eliminator->__invoke($sellerId);
            return new JsonResponse('Seller is remove successfully', 201);
        } catch (FailedRemoveSellerException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }
}