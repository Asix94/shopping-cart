<?php

namespace App\Tests\Unit\ShoppingCart\Product\Application;

use App\ShoppingCart\Product\Application\ProductCreator;
use App\ShoppingCart\Product\Domain\Name;
use App\ShoppingCart\Product\Domain\Price;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\ProductRepository;
use App\ShoppingCart\Product\Domain\SellerId;
use App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\AddProduct\AddProductRequest;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class ProductCreatorTest extends TestCase
{
    private ProductRepository|MockObject $repository;
    private ProductCreator $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepository::class);
        $this->useCase = new ProductCreator($this->repository);
    }

    public function testCreate(): void
    {
        $id = Uuid::uuid4()->toString();
        $sellerId = Uuid::uuid4()->toString();
        $name = 'Test Product';
        $price = 100;

        $productRequest = $this->createRequest($id, $sellerId, $name, $price);
        $product = $this->createProduct($productRequest);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($product);

        $this->useCase->__invoke($productRequest);
    }

    private function createRequest(string $id, string $sellerId, string $name, int $price): AddProductRequest
    {
        return AddProductRequest::productRequest(
            $id,
            $sellerId,
            $name,
            $price
        );
    }

    private function createProduct(AddProductRequest $request): Product
    {
        return new Product(
            ProductId::fromString($request->id()),
            SellerId::fromString($request->sellerId()),
            Name::fromString($request->name()),
            Price::fromFloat($request->price())
        );
    }
}