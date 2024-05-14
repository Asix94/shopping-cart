<?php

namespace App\ShoppingCart\Seller\Application;

use App\ShoppingCart\Seller\Domain\Seller;
use App\ShoppingCart\Seller\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\SellerName;
use App\ShoppingCart\Seller\Domain\SellerRepository;
use App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\AddSeller\AddSellerRequest;

class SellerCreator
{
    public function __construct(private readonly SellerRepository $sellerRepository) {}
    public function __invoke(AddSellerRequest $sellerRequest): void
    {
        $seller = new Seller(
            SellerId::fromString($sellerRequest->id()),
            SellerName::fromString($sellerRequest->name())
        );
        $this->sellerRepository->save($seller);
    }
}