<?php

namespace App\ShoppingCart\Seller\Application;

use App\ShoppingCart\Seller\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\SellerRepository;
use App\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\RemoveSeller\RemoveSellerRequest;

class SellerEliminator
{
    public function __construct(private readonly SellerRepository $sellerRepository) {}

    public function __invoke(RemoveSellerRequest $sellerRequest): void
    {
        $id = SellerId::fromString($sellerRequest->id());
        $this->sellerRepository->remove($id);
    }
}