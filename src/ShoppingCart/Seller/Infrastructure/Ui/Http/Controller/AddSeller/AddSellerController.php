<?php

namespace App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\AddSeller;

use App\ShoppingCart\Seller\Application\SellerCreator;
use App\ShoppingCart\Seller\Domain\Exceptions\FailedSaveSellerException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class AddSellerController
{
    public function __construct(private readonly SellerCreator $creator) {}
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $name = $request->get('name');
            $this->ValidateParams($name, 'name');

            $seller = AddSellerRequest::sellerRequest(
               Uuid::uuid4()->toString(),
               $name
            );
            $this->creator->__invoke($seller);
            return new JsonResponse('Seller is saved successfully', 201);
        } catch (FailedSaveSellerException $e) {
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