<?php

namespace App\Tests\Unit\ShoppingCart\Seller\Application;

use App\ShoppingCart\Seller\Application\SellerEliminator;
use App\ShoppingCart\Seller\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\SellerRepository;
use App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\RemoveSeller\RemoveSellerRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SellerEliminatorTest extends TestCase
{
    private SellerRepository|MockObject $repository;
    private SellerEliminator            $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(SellerRepository::class);
        $this->useCase = new SellerEliminator($this->repository);
    }

    public function testSellerEliminator(): void
    {
        $id = Uuid::uuid4()->toString();

        $sellerRequest = $this->createRequest($id);
        $sellerId = $this->createSellerId($sellerRequest);

        $this->repository
            ->expects($this->once())
            ->method('remove')
            ->with($sellerId);

        $this->useCase->__invoke($sellerRequest);
    }

    private function createRequest(string $id, ): RemoveSellerRequest
    {
        return RemoveSellerRequest::sellerRequest($id);
    }

    private function createSellerId(RemoveSellerRequest $request): SellerId
    {
        return SellerId::fromString($request->id());
    }
}