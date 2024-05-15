<?php

namespace App\Tests\Unit\ShoppingCart\Item\Application;

use App\ShoppingCart\Cart\Application\Item\ItemCreator;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedItemIsInCartException;
use App\ShoppingCart\Cart\Domain\Cart\Item;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use App\ShoppingCart\Cart\Domain\Cart\Quantity;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddItem\AddItemRequest;
use App\ShoppingCart\Product\Domain\Exceptions\FailedFindProductException;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\ProductRepository;
use App\ShoppingCart\Product\Domain\SellerId;
use App\Tests\Shared\CartMother;
use App\Tests\Shared\ItemMother;
use App\Tests\Shared\ProductMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class ItemCreatorTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private ProductRepository|MockObject $productRepository;
    private ItemCreator $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->useCase = new ItemCreator($this->repository, $this->productRepository);
    }

    public function testAddItem(): void
    {
        $cart = CartMother::create(new Items([]));
        $product = ProductMother::create(SellerId::fromString(Uuid::uuid4()->toString()));
        $itemRequest = AddItemRequest::itemRequest($cart->id()->toString(), $product->id()->toString());
        $item = ItemMother::create($product);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with(ProductId::fromString($itemRequest->productId()))
            ->willReturn($product);

        $this->repository
            ->expects($this->once())
            ->method('findItemByCartIdAndProductId')
            ->with($cart->id(), $product->id())
            ->willReturn(null);

        $this->repository
            ->expects($this->once())
            ->method('saveItemCart')
            ->with($cart->id(), $item);

        $this->useCase->__invoke($itemRequest);
    }

    public function testProductNotFound(): void
    {
        $cart = CartMother::create(new Items([]));
        $product = ProductMother::create(SellerId::fromString(Uuid::uuid4()->toString()));
        $itemRequest = AddItemRequest::itemRequest($cart->id()->toString(), $product->id()->toString());

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($product->id())
            ->willReturn(null);

        $this->expectException(FailedFindProductException::class);
        $this->useCase->__invoke($itemRequest);
    }

    public function testItemInCart(): void
    {
        $cart = CartMother::create(new Items([]));
        $product = ProductMother::create(SellerId::fromString(Uuid::uuid4()->toString()));
        $itemRequest = AddItemRequest::itemRequest($cart->id()->toString(), $product->id()->toString());
        $item = $this->createItem($product);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($product->id())
            ->willReturn($product);

        $this->repository
            ->expects($this->once())
            ->method('findItemByCartIdAndProductId')
            ->with($cart->id(), $product->id())
            ->willReturn($item);

        $this->expectException(FailedItemIsInCartException::class);
        $this->useCase->__invoke($itemRequest);
    }

    private function createItem(Product $product): Item
    {
        return new Item(
            $product,
            Quantity::fromInt(1),
        );
    }
}