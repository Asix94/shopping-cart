<?php

namespace App\Tests\Unit\ShoppingCart\Product\Application;

use App\ShoppingCart\Product\Application\ProductCreator;
use App\ShoppingCart\Product\Application\ProductEliminator;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\ProductRepository;
use App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\RemoveProduct\RemoveProductRequest;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class ProductEliminatorTest extends TestCase
{
    private ProductRepository|MockObject $repository;
    private ProductEliminator $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepository::class);
        $this->useCase = new ProductEliminator($this->repository);
    }

    public function testSellerEliminator(): void
    {
        $id = Uuid::uuid4()->toString();

        $productRequest = $this->createRequest($id);
        $productId = $this->createSellerId($productRequest);

        $this->repository
            ->expects($this->once())
            ->method('remove')
            ->with($productId);

        $this->useCase->__invoke($productRequest);
    }

    private function createRequest(string $id, ): RemoveProductRequest
    {
        return RemoveProductRequest::productRequest($id);
    }

    private function createSellerId(RemoveProductRequest $request): ProductId
    {
        return ProductId::fromString($request->id());
    }
}