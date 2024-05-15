<?php

namespace App\Tests\Unit\ShoppingCart\Item\Application;

use App\ShoppingCart\Cart\Application\Item\ItemDecrement;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedItemNotFoundException;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\DecreaseItem\DecreaseItemRequest;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\IncreaseItem\IncreaseItemRequest;
use App\ShoppingCart\Product\Domain\SellerId;
use App\Tests\Shared\CartMother;
use App\Tests\Shared\ItemMother;
use App\Tests\Shared\ProductMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class ItemDecrementTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private ItemDecrement $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->useCase = new ItemDecrement($this->repository);
    }

    public function testDecrementItem(): void
    {
        $cart = CartMother::create(new Items([]));
        $product = ProductMother::create(SellerId::fromString(Uuid::uuid4()->toString()));
        $item = ItemMother::create($product);
        $item->increaseQuantity();
        $request = DecreaseItemRequest::decreaseItemRequest($cart->id()->toString(), $product->id()->toString());

        $this->repository
            ->expects($this->once())
            ->method('findItemByCartIdAndProductId')
            ->with($cart->id(), $product->id())
            ->willReturn($item);

        $this->repository
            ->expects($this->once())
            ->method('updateQuantity')
            ->with($cart->id(), $item);

        $this->useCase->__invoke($request);
    }

    public function testDecrementItemDeleteItem(): void
    {
        $cart = CartMother::create(new Items([]));
        $product = ProductMother::create(SellerId::fromString(Uuid::uuid4()->toString()));
        $item = ItemMother::create($product);
        $request = DecreaseItemRequest::decreaseItemRequest($cart->id()->toString(), $product->id()->toString());

        $this->repository
            ->expects($this->once())
            ->method('findItemByCartIdAndProductId')
            ->with($cart->id(), $product->id())
            ->willReturn($item);

        $this->repository
            ->expects($this->once())
            ->method('removeItemCart')
            ->with($cart->id(), $product->id());

        $this->useCase->__invoke($request);
    }

    public function testExceptionItemNotFound(): void
    {
        $cart = CartMother::create(new Items([]));
        $product = ProductMother::create(SellerId::fromString(Uuid::uuid4()->toString()));
        $request = DecreaseItemRequest::decreaseItemRequest($cart->id()->toString(), $product->id()->toString());

        $this->repository
            ->expects($this->once())
            ->method('findItemByCartIdAndProductId')
            ->with($cart->id(), $product->id())
            ->willReturn(null);

        $this->expectException(FailedItemNotFoundException::class);
        $this->useCase->__invoke($request);
    }
}