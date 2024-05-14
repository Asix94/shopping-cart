<?php

namespace App\ShoppingCart\Seller\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Seller\Domain\Exceptions\FailedRemoveSellerException;
use App\ShoppingCart\Seller\Domain\Exceptions\FailedSaveSellerException;
use App\ShoppingCart\Seller\Domain\Seller;
use App\ShoppingCart\Seller\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\SellerName;
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

    public function remove(SellerId $id): void
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->executeStatement(
                "DELETE FROM seller WHERE id = :id",
                [
                    'id' => $id->toString(),
                ]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw new FailedRemoveSellerException('Failed to remove seller: ' . $e->getMessage());
        }
    }

    public function findById(SellerId $id): ?Seller
    {
        $sellerQueryBuilder = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('seller')
            ->where('id = :id')
            ->setMaxResults(1)
            ->setParameter('id', $id->toString());

        $seller = $sellerQueryBuilder->executeQuery()->fetchAssociative();

        if (!$seller) { return null; }
        return new Seller(
            SellerId::fromString($seller['id']),
            SellerName::fromString($seller['name'])
        );
    }
}