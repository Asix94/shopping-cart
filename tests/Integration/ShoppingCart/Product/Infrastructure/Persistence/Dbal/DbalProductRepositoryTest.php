<?php

namespace App\Tests\Integration\ShoppingCart\Product\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Product\Domain\Price;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\Name;
use App\ShoppingCart\Product\Domain\SellerId;
use App\ShoppingCart\Product\Infrastructure\Persistence\Dbal\DbalProductRepository;
use App\ShoppingCart\Seller\Domain\Seller;
use App\Tests\Shared\ProductMother;
use App\Tests\Shared\SellerMother;
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
        $seller = SellerMother::create();
        $product = ProductMother::create(SellerId::fromString($seller->id()->toString()));

        $this->saveSeller($seller);
        $this->repository->save($product);
        $productFinder = $this->findProduct($product->id());

        $this->assertEquals($product->id(), $productFinder->id());
        $this->assertEquals($product->sellerId(), $productFinder->sellerId());
        $this->assertEquals($product->name(), $productFinder->name());
        $this->assertEquals($product->price(), $productFinder->price());

        $this->removeProduct($product->id());
        $this->removeSeller($seller->id());
    }

    public function testRemove(): void
    {
        $seller = Sellermother::create();
        $product = ProductMother::create(SellerId::fromString($seller->id()->toString()));

        $this->saveSeller($seller);
        $this->saveProduct($product);

        $this->repository->remove($product->id());
        $this->removeSeller($seller->id());

        $product = $this->findProduct($product->id());
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

    private function saveSeller(Seller $seller): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "INSERT INTO seller (id, name)
                     VALUE (:id, :name)",
            [
                'id' => $seller->id()->toString(),
                'name' => $seller->name()->toString(),
            ]
        );
        $this->connection->commit();
    }

    private function saveProduct(Product $product): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "INSERT INTO product (id, seller_id, name, price)
                     VALUE (:id, :sellerId, :name, :price)",
            [
                'id' => $product->id()->toString(),
                'sellerId' => $product->sellerId()->toString(),
                'name' => $product->name()->toString(),
                'price' => $product->price()->toString(),
            ]
        );
        $this->connection->commit();
    }

    private function removeSeller(\App\ShoppingCart\Seller\Domain\SellerId $sellerId): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "DELETE FROM seller WHERE id = :id",
            [
                'id' => $sellerId->toString(),
            ]
        );
        $this->connection->commit();
    }

    private function removeProduct(ProductId $id): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "DELETE FROM product WHERE id = :id",
            [
                'id' => $id->toString(),
            ]
        );
        $this->connection->commit();
    }
}