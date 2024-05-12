<?php

namespace App\Tests\Integration\ShoppingCart\Cart\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Cart\Domain\Cart\Cart;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Infrastructure\Persistence\Dbal\DbalCartRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Container;

final class DbalCartRepositoryTest extends KernelTestCase
{
    private mixed $connection;
    private DbalCartRepository $repository;

    public function setUp(): void
    {
        $this->connection       = $this->getContainer()->get(Connection::class);
        $this->repository = new DbalCartRepository($this->connection);
    }

    public function testSave(): void
    {
        $id = Uuid::uuid4()->toString();
        $cart = new Cart(
            CartId::fromString($id)
        );

        $this->repository->save($cart);
        $cartFinder = $this->findCart($cart->id());

        $this->assertEquals($cartFinder->id(), $cart->id());
    }

    private function findCart(CartId $id): ?Cart
    {
        $cart = $this->connection->createQueryBuilder()
                                    ->select('*')
                                    ->from('cart', 'c')
                                    ->where('c.id = :id')
                                    ->setParameter('id', $id->toString())
                                    ->executeQuery()->fetchAssociative();

        if (!$cart) {
            return null;
        }
        return new Cart(
            CartId::fromString($cart['id']),
        );
    }
}