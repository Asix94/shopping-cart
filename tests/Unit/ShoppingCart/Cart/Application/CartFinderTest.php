<?php

namespace App\Tests\Unit\ShoppingCart\Cart\Application;

use App\ShoppingCart\Cart\Application\Cart\CartFinder;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use App\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\Cart\TotalAmount\TotalAmountRequest;
use App\ShoppingCart\Product\Domain\SellerId;
use App\Tests\Shared\CartMother;
use App\Tests\Shared\ItemMother;
use App\Tests\Shared\ProductMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CartFinderTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private CartFinder $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->useCase = new CartFinder($this->repository);
    }

    public function testFindAmount(): void
    {
        $product1 = ProductMother::create(SellerId::fromString(Uuid::uuid4()->toString()));
        $product2 = ProductMother::create(SellerId::fromString(Uuid::uuid4()->toString()));
        $cart = CartMother::create(new Items([
            ItemMother::create($product1),
            ItemMother::create($product2)
        ]));
        $cartRequest = TotalAmountRequest::totalAmountRequest($cart->id()->toString());

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($cart->id())
            ->willReturn($cart);

        $cartFind = $this->useCase->__invoke($cartRequest);
        $this->assertEquals($cartFind->id(), $cart->id());
    }
}