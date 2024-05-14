<?php

namespace App\ShoppingCart\Product\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Product\Domain\Exceptions\FailedFindProductException;
use App\ShoppingCart\Product\Domain\Exceptions\FailedSaveProductException;
use App\ShoppingCart\Product\Domain\Name;
use App\ShoppingCart\Product\Domain\Price;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\ProductRepository;
use App\ShoppingCart\Product\Domain\SellerId;
use Doctrine\DBAL\Connection;

final class DbalProductRepository implements ProductRepository
{
    public function __construct(private Connection $connection,) {}

    public function save(Product $product): void
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->executeStatement(
                "INSERT INTO product (id, seller_id, name, price)
                     VALUE (:id, :sellerId, :name, :price)",
                [
                    'id' => $product->id()->toString(),
                    'sellerId' => $product->sellerId()->toString(),
                    'name' => $product->name()->toString(),
                    'price' => $product->price()->toFloat(),
                ]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw new FailedSaveProductException('Failed to save product: ' . $e->getMessage());
        }
    }

    public function remove(ProductId $id): void
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->executeStatement(
                "DELETE FROM product WHERE id = :id",
                [
                    'id' => $id->toString(),
                ]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw new FailedRemoveProductException('Failed to remove product: ' . $e->getMessage());
        }
    }

    public function findById(ProductId $id): ?Product
    {
        try {
            $queryBuilder = $this->connection
                ->createQueryBuilder()
                ->select('*')
                ->from('product')
                ->where('id = :id')
                ->setMaxResults(1)
                ->setParameter('id', $id->toString());

            $product = $queryBuilder->executeQuery()->fetchAssociative();
            if (!$product) { return null; }
            return new Product(
                ProductId::fromString($product['id']),
                SellerId::fromString($product['sellerId']),
                Name::fromString($product['name']),
                Price::fromFloat($product['price']),
            );
        } catch (\Exception $e) {
            throw new FailedFindProductException('Failed to find product by id: ' . $e->getMessage());
        }
    }
}