<?php

namespace App\ShoppingCart\Cart\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Cart\Domain\Cart\Cart;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedConfirmCartException;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedSaveCartException;
use Doctrine\DBAL\Connection;

final class DbalCartRepository implements CartRepository
{
    public function __construct(private Connection $connection,) {}

    public function save(Cart $cart): void
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->executeStatement(
                "INSERT INTO cart (id)
                     VALUE (:id)",
                [
                    'id' => $cart->id()->toString(),
                ]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw new FailedSaveCartException('Failed to save cart: ' . $e->getMessage());
        }
    }

    public function cartConfirmed(CartId $cartId): void
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->executeStatement(
                "UPDATE cart SET confirmed = 1 WHERE id = :id",
                [
                    'id' => $cartId->toString(),
                ]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw new FailedConfirmCartException('Failed to confirm cart: ' . $e->getMessage());
        }
    }
}