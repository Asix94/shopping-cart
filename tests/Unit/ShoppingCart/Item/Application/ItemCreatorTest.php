<?php

namespace App\Tests\Unit\ShoppingCart\Item\Application;

use App\ShoppingCart\Cart\Application\Item\ItemCreator;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedItemIsInCartException;
use App\ShoppingCart\Cart\Domain\Cart\Item;
use App\ShoppingCart\Cart\Domain\Cart\Quantity;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddItem\AddItemRequest;
use App\ShoppingCart\Product\Domain\Exceptions\FailedFindProductException;
use App\ShoppingCart\Product\Domain\Name;
use App\ShoppingCart\Product\Domain\Price;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\ProductRepository;
use App\ShoppingCart\Product\Domain\SellerId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ItemCreatorTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private ProductRepository|MockObject $productRepository;
    private ItemCreator $useCase;
    private AddItemRequest $itemRequest;

    public function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->useCase = new ItemCreator($this->repository, $this->productRepository);
        $cart_id = '48bfe4fc-c7fd-4ac7-ad45-9889d8a833bb';
        $product_id = 'cd68a499-66d2-46e8-a0ea-13db1746d326';
        $this->itemRequest = AddItemRequest::itemRequest($cart_id, $product_id);
    }

    public function testAddItem(): void
    {
        $product = $this->createProduct($this->itemRequest->productId());
        $item = $this->createItem($product);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with(ProductId::fromString($this->itemRequest->productId()))
            ->willReturn($product);

        $this->repository
            ->expects($this->once())
            ->method('findItemByCartIdAndProductId')
            ->with(CartId::fromString($this->itemRequest->cartId()), ProductId::fromString($this->itemRequest->productId()))
            ->willReturn(null);

        $this->repository
            ->expects($this->once())
            ->method('saveItemCart')
            ->with(CartId::fromString($this->itemRequest->cartId()), $item);

        $this->useCase->__invoke($this->itemRequest);
    }

    public function testProductNotFound(): void
    {
        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with(ProductId::fromString($this->itemRequest->productId()))
            ->willReturn(null);

        $this->expectException(FailedFindProductException::class);
        $this->useCase->__invoke($this->itemRequest);
    }

    public function testItemInCart(): void
    {
        $product = $this->createProduct($this->itemRequest->productId());
        $item = $this->createItem($product);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with(ProductId::fromString($this->itemRequest->productId()))
            ->willReturn($product);

        $this->repository
            ->expects($this->once())
            ->method('findItemByCartIdAndProductId')
            ->with(CartId::fromString($this->itemRequest->cartId()), ProductId::fromString($this->itemRequest->productId()))
            ->willReturn($item);

        $this->expectException(FailedItemIsInCartException::class);
        $this->useCase->__invoke($this->itemRequest);
    }

    private function createProduct(string $productId): Product
    {
        return new Product(
            ProductId::fromString($productId),
            SellerId::fromString('7d135250-3a2b-42e0-8956-2ccc24b03f01'),
            Name::fromString('test'),
            Price::fromFloat(20),
        );
    }

    private function createItem(Product $product): Item
    {
        return new Item(
            $product,
            Quantity::fromInt(1),
        );
    }
}