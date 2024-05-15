<?php

namespace App\Tests\Unit\ShoppingCart\Item\Application;

use App\ShoppingCart\Cart\Application\Item\ItemEliminator;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveItem\RemoveItemRequest;
use App\ShoppingCart\Product\Domain\SellerId;
use App\Tests\Shared\CartMother;
use App\Tests\Shared\ProductMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class ItemEliminatorTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private ItemEliminator $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->useCase = new ItemEliminator($this->repository);
    }

    public function testRemoveItem(): void
    {
        $product = ProductMother::create(SellerId::fromString(Uuid::uuid4()->toString()));
        $cart = CartMother::create(new Items([]));
        $itemRequest = RemoveItemRequest::removeItemRequest($cart->id()->toString(), $product->id()->toString());

        $this->repository
            ->expects($this->once())
            ->method('removeItemCart')
            ->with($cart->id(), $product->id());

        $this->useCase->__invoke($itemRequest);
    }
}