<?php

namespace App\Tests\Unit\ShoppingCart\Product\Application;

use App\ShoppingCart\Product\Application\ProductCreator;
use App\ShoppingCart\Product\Domain\ProductRepository;
use App\ShoppingCart\Product\Domain\SellerId;
use App\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\AddProduct\AddProductRequest;
use App\ShoppingCart\Seller\Domain\Exceptions\SellerNotFoundException;
use App\ShoppingCart\Seller\Domain\SellerRepository;
use App\Tests\Shared\ProductMother;
use App\Tests\Shared\SellerMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ProductCreatorTest extends TestCase
{
    private ProductRepository|MockObject $repository;
    private SellerRepository|MockObject $sellerRepository;
    private ProductCreator $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepository::class);
        $this->sellerRepository = $this->createMock(SellerRepository::class);
        $this->useCase = new ProductCreator($this->repository, $this->sellerRepository);
    }

    public function testCreate(): void
    {
        $seller = SellerMother::create();
        $product = ProductMother::create(SellerId::fromString($seller->id()->toString()));
        $productRequest = $this->createRequest(
            $product->id()->toString(),
            $product->sellerId()->toString(),
            $product->name()->toString(),
            $product->price()->toString());

        $this->sellerRepository
            ->expects($this->once())
            ->method('findById')
            ->with($seller->id())
            ->willReturn($seller);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($product);

        $this->useCase->__invoke($productRequest);
    }

    public function testSellerNotFound(): void
    {
        $seller = SellerMother::create();
        $product = ProductMother::create(SellerId::fromString($seller->id()->toString()));
        $productRequest = $this->createRequest(
            $product->id()->toString(),
            $product->sellerId()->toString(),
            $product->name()->toString(),
            $product->price()->toString());

        $this->sellerRepository
            ->expects($this->once())
            ->method('findById')
            ->with($seller->id())
            ->willReturn(null);

        $this->expectException(SellerNotFoundException::class);
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
}