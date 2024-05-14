<?php

namespace App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\AddSeller;

use App\ShoppingCart\Seller\Application\SellerCreator;
use App\ShoppingCart\Seller\Domain\Exceptions\FailedSaveSellerException;
use App\ShoppingCart\Shared\Domain\Exception\ValidationException;
use App\ShoppingCart\Shared\Domain\ValidateRequest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class AddSellerController
{
    public function __construct(private readonly SellerCreator $creator) {}
    public function __invoke(Request $request): JsonResponse
    {
        try {
            ValidateRequest::validate($request, ['name']);

            $seller = AddSellerRequest::sellerRequest(
               Uuid::uuid4()->toString(),
                $request->get('name')
            );

            $this->creator->__invoke($seller);
            return new JsonResponse('Seller is saved successfully', 201);
        } catch (FailedSaveSellerException $e) {
            return new JsonResponse($e->getMessage(), 500);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }

    }
}