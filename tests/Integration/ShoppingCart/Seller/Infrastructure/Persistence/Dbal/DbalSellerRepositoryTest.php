<?php

namespace App\Tests\Integration\ShoppingCart\Seller\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Seller\Domain\Seller;
use App\ShoppingCart\Seller\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\SellerName;
use App\ShoppingCart\Seller\Infrastructure\Persistence\Dbal\DbalSellerRepository;
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
    public function testSave(): void
    {
        $id = SellerId::fromString(Uuid::uuid4()->toString());
        $name = SellerName::fromString('test');

        $seller = new Seller($id, $name);
        $this->repository->save($seller);

        $sellerFind = $this->findSeller($id);

        $this->assertEquals($sellerFind->id(), $seller->id());
        $this->assertEquals($sellerFind->name(), $seller->name());

        $this->deleteSeller($id);
    }

    private function findSeller(SellerId $id): Seller
    {
        $seller = $this->connection->createQueryBuilder()
                                       ->select('*')
                                       ->from('seller', 's')
                                       ->where('s.id = :id')
                                       ->setParameter('id', $id->toString())
                                       ->executeQuery()->fetchAssociative();

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
}