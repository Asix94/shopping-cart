<?php

namespace App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\RemoveProduct;

use App\ShoppingCart\Product\Application\ProductEliminator;
use App\ShoppingCart\Product\Domain\Exceptions\FailedRemoveProductException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class RemoveProductController
{
    public function __construct(private readonly ProductEliminator $eliminator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $id = $request->get('id');
            $this->validateParam($id);
            $productId = RemoveProductRequest::productRequest($id);

            $this->eliminator->__invoke($productId);
            return new JsonResponse('Product is remove successfully', 201);
        } catch (FailedRemoveProductException $e) {
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