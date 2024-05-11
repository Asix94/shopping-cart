<?php

namespace App\ShoppingCart\Seller\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Seller\Domain\FailedSaveSellerException;
use App\ShoppingCart\Seller\Domain\Seller;
use App\ShoppingCart\Seller\Domain\SellerRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class DbalSellerRepository implements SellerRepository
{
    public function __construct(private Connection $connection,) {}

    /**
     * @throws FailedSaveSellerException
     * @throws Exception
     */
    public function save(Seller $seller): void
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->executeStatement(
                "INSERT INTO seller (id, name)
                     VALUE (:id, :name)",
                [
                    'id' => $seller->id()->toString(),
                    'name' => $seller->name(),
                ]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw new FailedSaveSellerException('Failed to save seller: ' . $e->getMessage());
        }

    }
}