<?php

namespace App\Tests\Unit\ShoppingCart\Seller\Application;

use App\ShoppingCart\Seller\Application\SellerCreator;
use App\ShoppingCart\Seller\Domain\SellerRepository;
use App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\AddSeller\AddSellerRequest;
use App\Tests\Shared\SellerMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SellerCreatorTest extends TestCase
{
    private SellerRepository|MockObject $repository;
    private SellerCreator $useCase;

    public function setUp(): void
    {
        $this->repository = $this->createMock(SellerRepository::class);
        $this->useCase = new SellerCreator($this->repository);
    }
    public function testSellerCreator(): void
    {
        $seller = SellerMother::create();
        $sellerRequest = $this->createRequest($seller->id()->toString(), $seller->name()->toString());

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($seller);

        $this->useCase->__invoke($sellerRequest);
    }

    private function createRequest(string $id, string $name): AddSellerRequest
    {
        return AddSellerRequest::sellerRequest(
            $id,
            $name
        );
    }
}