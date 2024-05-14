<?php

namespace App\Tests\Unit\ShoppingCart\Seller\Application;

use App\ShoppingCart\Seller\Application\SellerCreator;
use App\ShoppingCart\Seller\Domain\Exceptions\FailedSaveSellerException;
use App\ShoppingCart\Seller\Domain\Seller;
use App\ShoppingCart\Seller\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\SellerName;
use App\ShoppingCart\Seller\Domain\SellerRepository;
use App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\AddSeller\AddSellerRequest;
use App\Tests\Unit\ShoppingCart\Seller\Application\Mothers\AddRequestMother;
use App\Tests\Unit\ShoppingCart\Seller\Application\Mothers\SellerMother;
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
        $id = Uuid::uuid4()->toString();
        $name = 'Test';

        $sellerRequest = AddRequestMother::createRequest($id, $name);
        $seller = SellerMother::createSeller($sellerRequest);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($seller);

        $this->useCase->__invoke($sellerRequest);
    }
}