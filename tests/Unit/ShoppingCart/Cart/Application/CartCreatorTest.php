<?php

namespace App\Tests\Unit\ShoppingCart\Cart\Application;

use App\ShoppingCart\Cart\Application\Cart\CartCreator;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddCart\AddCartRequest;
use App\Tests\Shared\CartMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CartCreatorTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private CartCreator $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->useCase = new CartCreator($this->repository);
    }

    public function testCreate(): void
    {
        $cart = CartMother::create(new Items([]));
        $cartRequest = AddCartRequest::addCartRequest($cart->id()->toString());

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($cart);

        $this->useCase->__invoke($cartRequest);
    }
}