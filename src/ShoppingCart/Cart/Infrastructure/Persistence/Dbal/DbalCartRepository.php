<?php

namespace App\ShoppingCart\Cart\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Cart\Domain\Cart\Cart;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\CartRepository;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedConfirmCartException;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedRemoveItemCartException;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedSaveCartException;
use App\ShoppingCart\Cart\Domain\Cart\Exceptions\FailedSaveItemCartException;
use App\ShoppingCart\Cart\Domain\Cart\Item;
use App\ShoppingCart\Product\Domain\ProductId;
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

    public function saveItemCart(CartId $cartId, Item $item): void
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->executeStatement(
                "INSERT INTO cart_item (cart_id, product_id, quantity) VALUE (:cart_id, :product_id, :quantity)",
                [
                    'cart_id' => $cartId->toString(),
                    'product_id' => $item->product()->id()->toString(),
                    'quantity' => $item->quantity()->toInt(),
                ]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw new FailedSaveItemCartException('Failed to save item cart: ' . $e->getMessage());
        }
    }

    public function removeItemCart(CartId $cartId, ProductId $productId): void
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->executeStatement(
                "DELETE FROM cart_item WHERE cart_id = :cart_id AND product_id = :product_id",
                [
                    'cart_id' => $cartId->toString(),
                    'product_id' => $productId->toString(),
                ]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw new FailedRemoveItemCartException('Failed to remove item cart: ' . $e->getMessage());
        }
    }
}