<?php

namespace App\Tests\Integration\ShoppingCart\Product\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Product\Domain\Price;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\Name;
use App\ShoppingCart\Product\Domain\SellerId;
use App\ShoppingCart\Product\Infrastructure\Persistence\Dbal\DbalProductRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\DBAL\Connection;

final class DbalProductRepositoryTest extends KernelTestCase
{
    private mixed                 $connection;
    private DbalProductRepository $repository;

    public function setUp(): void
    {
        $this->connection       = $this->getContainer()->get(Connection::class);
        $this->repository = new DbalProductRepository($this->connection);
    }

    public function testSave(): void
    {
        $id = Uuid::uuid4()->toString();
        $sellerId = Uuid::uuid4()->toString();
        $sellerName = 'seller name';
        $name = 'product name';
        $price = 20.0;

        $this->saveSeller($sellerId, $sellerName);

        $product = new Product(
            ProductId::fromString($id),
            SellerId::fromString($sellerId),
            Name::fromString($name),
            Price::fromFloat($price)
        );

        $this->repository->save($product);
        $productFinder = $this->findProduct(ProductId::fromString($id));

        $this->assertEquals($product->id(), $productFinder->id());
        $this->assertEquals($product->sellerId(), $productFinder->sellerId());
        $this->assertEquals($product->name(), $productFinder->name());
        $this->assertEquals($product->price(), $productFinder->price());

        $this->removeProduct($id);
        $this->removeSeller($sellerId);
    }

    public function testRemove(): void
    {
        $id = Uuid::uuid4()->toString();
        $sellerId = Uuid::uuid4()->toString();
        $sellerName = 'seller name';
        $name = 'product name';
        $price = 20.0;

        $this->saveSeller($sellerId, $sellerName);
        $this->saveProduct($id, $sellerId, $name, $price);

        $this->repository->remove(ProductId::fromString($id));
        $this->removeSeller($sellerId);

        $product = $this->findProduct(ProductId::fromString($id));
        $this->assertNull($product);
    }

    private function findProduct(ProductId $id): ?Product
    {
        $product = $this->connection->createQueryBuilder()
                                   ->select('*')
                                   ->from('product', 'p')
                                   ->where('p.id = :id')
                                   ->setParameter('id', $id->toString())
                                   ->executeQuery()->fetchAssociative();

        if (!$product) {
            return null;
        }
        return new Product(
            ProductId::fromString($product['id']),
            SellerId::fromString($product['seller_id']),
            Name::fromString($product['name']),
            Price::fromFloat($product['price']),);
    }

    private function saveSeller(string $sellerId, string $sellerName): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "INSERT INTO seller (id, name)
                     VALUE (:id, :name)",
            [
                'id' => $sellerId,
                'name' => $sellerName,
            ]
        );
        $this->connection->commit();
    }

    private function saveProduct(string $productId, string $sellerId, string $name, float $price): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "INSERT INTO product (id, seller_id, name, price)
                     VALUE (:id, :sellerId, :name, :price)",
            [
                'id' => $productId,
                'sellerId' => $sellerId,
                'name' => $name,
                'price' => $price,
            ]
        );
        $this->connection->commit();
    }

    private function removeSeller(string $sellerId): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "DELETE FROM seller WHERE id = :id",
            [
                'id' => $sellerId,
            ]
        );
        $this->connection->commit();
    }

    private function removeProduct(string $id): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "DELETE FROM product WHERE id = :id",
            [
                'id' => $id,
            ]
        );
        $this->connection->commit();
    }
}