<?php

namespace App\Tests\Unit\ShoppingCart\Item\Application;

use App\ShoppingCart\Cart\Application\Item\ItemsEliminator;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\RemoveAllItems\RemoveAllItemRequest;
use App\Tests\Shared\CartMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ItemsEliminatorTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private ItemsEliminator $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->useCase = new ItemsEliminator($this->repository);
    }

    public function testRemoveAllItems(): void
    {
        $cart = CartMother::create(new Items([]));
        $itemRequest = RemoveAllItemRequest::removeAllItemRequest($cart->id()->toString());

        $this->repository
            ->expects($this->once())
            ->method('removeAllItemCart')
            ->with($cart->id());

        $this->useCase->__invoke($itemRequest);
    }
}