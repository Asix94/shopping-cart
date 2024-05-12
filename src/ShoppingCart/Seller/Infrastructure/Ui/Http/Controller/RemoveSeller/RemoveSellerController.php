<?php

namespace App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\RemoveSeller;

use App\ShoppingCart\Seller\Application\SellerEliminator;
use App\ShoppingCart\Seller\Domain\Exceptions\FailedRemoveSellerException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class RemoveSellerController
{
    public function __construct(private readonly SellerEliminator $eliminator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $id = $request->get('id');
            $this->validateParam($id);
            $sellerId = RemoveSellerRequest::sellerRequest($id);

            $this->eliminator->__invoke($sellerId);
            return new JsonResponse('Seller is remove successfully', 201);
        } catch (FailedRemoveSellerException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }
    }

    private function validateParam(string $nameParam): void
    {
        if (!$nameParam) {
            throw new \Exception('Parameter ' . $nameParam . ' is required.');
        }
    }
}