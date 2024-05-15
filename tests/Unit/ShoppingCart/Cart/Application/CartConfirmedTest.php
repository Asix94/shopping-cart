<?php

namespace App\Tests\Unit\ShoppingCart\Cart\Application;

use App\ShoppingCart\Cart\Application\Cart\CartConfirmed;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\ConfirmedCart\ConfirmedCartRequest;
use App\Tests\Shared\CartMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CartConfirmedTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private CartConfirmed $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->useCase = new CartConfirmed($this->repository);
    }

    public function testConfirmed(): void
    {
        $cart = CartMother::create(new Items([]));
        $cartRequest = ConfirmedCartRequest::confirmedCartRequest($cart->id()->toString());

        $this->repository
            ->expects($this->once())
            ->method('cartConfirmed')
            ->with($cart->id());

        $this->useCase->__invoke($cartRequest);
    }
}