<?php

namespace App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\AddProduct;

use App\ShoppingCart\Product\Application\ProductCreator;
use App\ShoppingCart\Product\Domain\Exceptions\FailedSaveProductException;
use App\ShoppingCart\Seller\Domain\Exceptions\SellerNotFoundException;
use App\ShoppingCart\Shared\Domain\Exception\ValidationException;
use App\ShoppingCart\Shared\Domain\ValidateRequest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class AddProductController
{
    public function __construct(private readonly ProductCreator $creator) {}
    public function __invoke(Request $request): JsonResponse
    {
        try {
            ValidateRequest::validate($request, ['sellerId', 'name', 'price']);

            $product = AddProductRequest::productRequest(
                Uuid::uuid4()->toString(),
                $request->get('sellerId'),
                $request->get('name'),
                $request->get('price')
            );

            $this->creator->__invoke($product);

            return new JsonResponse('Product is saved successfully', 201);
        } catch (FailedSaveProductException  $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (SellerNotFoundException $e) {
            return new JsonResponse($e->getMessage(), 404);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}