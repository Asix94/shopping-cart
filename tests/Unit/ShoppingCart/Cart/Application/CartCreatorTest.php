<?php

namespace App\Tests\Unit\ShoppingCart\Cart\Application;

use App\ShoppingCart\Cart\Application\Cart\CartCreator;
use App\ShoppingCart\Cart\Domain\Cart\Cart;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\AddCart\AddCartRequest;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

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
        $id = Uuid::uuid4()->toString();

        $cartRequest = AddCartRequest::addCartRequest($id);
        $cart = new Cart(CartId::fromString($id));

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($cart);

        $this->useCase->__invoke($cartRequest);
    }
}