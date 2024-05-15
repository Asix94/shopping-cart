<?php

namespace App\Tests\Integration\ShoppingCart\Seller\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Seller\Domain\Seller;
use App\ShoppingCart\Seller\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\SellerName;
use App\ShoppingCart\Seller\Infrastructure\Persistence\Dbal\DbalSellerRepository;
use App\Tests\Shared\SellerMother;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DbalSellerRepositoryTest extends KernelTestCase
{
    private mixed                $connection;
    private DbalSellerRepository $repository;

    public function setUp(): void
    {
        $this->connection       = $this->getContainer()->get(Connection::class);
        $this->repository = new DbalSellerRepository($this->connection);
    }
    public function testSaveSeller(): void
    {
        $seller = SellerMother::create();
        $this->repository->save($seller);
        $sellerFind = $this->findSeller($seller->id());

        $this->assertEquals($sellerFind->id(), $seller->id());
        $this->assertEquals($sellerFind->name(), $seller->name());

        $this->deleteSeller($seller->id());
    }

    public function testRemove(): void
    {
        $seller = SellerMother::create();
        $this->saveSeller($seller);
        $this->repository->remove($seller->id());
        $sellerFind = $this->findSeller($seller->id());

        $this->assertNull($sellerFind);
    }

    public function testFindById(): void
    {
        $seller = SellerMother::create();
        $this->saveSeller($seller);

        $sellerFind = $this->repository->findById($seller->id());

        $this->assertEquals($sellerFind->id(), $seller->id());
        $this->assertEquals($sellerFind->name(), $seller->name());

        $this->deleteSeller($seller->id());
    }

    private function findSeller(SellerId $id): ?Seller
    {
        $seller = $this->connection->createQueryBuilder()
                                       ->select('*')
                                       ->from('seller', 's')
                                       ->where('s.id = :id')
                                       ->setParameter('id', $id->toString())
                                       ->executeQuery()->fetchAssociative();

        if (!$seller) { return null; }
        return new Seller(SellerId::fromString($seller['id']), SellerName::fromString($seller['name']));
    }

    private function deleteSeller(SellerId $id): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "DELETE FROM seller WHERE id = :id",
            [
                'id' => $id->toString(),
            ]
        );
        $this->connection->commit();
    }

    private function saveSeller(Seller $seller): void
    {
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
    }
}