<?php

namespace App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\AddProduct;

use App\ShoppingCart\Product\Application\ProductCreator;
use App\ShoppingCart\Product\Domain\Exceptions\FailedSaveProductException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class AddProductController
{
    public function __construct(private readonly ProductCreator $creator) {}
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $sellerId = $request->query->get('sellerId');
            $name = $request->query->get('name');
            $price = $request->query->get('price');

            $this->ValidateParams($sellerId, 'sellerId');
            $this->ValidateParams($name, 'name');
            $this->ValidateParams($price, 'price');

            $product = AddProductRequest::productRequest(
                Uuid::uuid4()->toString(),
                $sellerId,
                $name,
                $price
            );

            $this->creator->__invoke($product);

            return new JsonResponse('Product is saved successfully', 201);
        } catch (FailedSaveProductException  $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (\Exception $e) {
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