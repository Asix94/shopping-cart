<?php

namespace App\Tests\Integration\ShoppingCart\Cart\Infrastructure\Persistence\Dbal;

use App\ShoppingCart\Cart\Domain\Cart\Cart;
use App\ShoppingCart\Cart\Domain\Cart\CartId;
use App\ShoppingCart\Cart\Domain\Cart\Item;
use App\ShoppingCart\Cart\Domain\Cart\Items;
use App\ShoppingCart\Cart\Domain\Cart\Quantity;
use App\ShoppingCart\Cart\Infrastructure\Persistence\Dbal\DbalCartRepository;
use App\ShoppingCart\Product\Domain\Name;
use App\ShoppingCart\Product\Domain\Price;
use App\ShoppingCart\Product\Domain\Product;
use App\ShoppingCart\Product\Domain\ProductId;
use App\ShoppingCart\Product\Domain\SellerId;
use App\ShoppingCart\Seller\Domain\Seller;
use App\Tests\Shared\CartMother;
use App\Tests\Shared\ItemMother;
use App\Tests\Shared\ProductMother;
use App\Tests\Shared\SellerMother;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\DBAL\Connection;

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
        $cart = CartMother::create(new Items([]));
        $this->repository->save($cart);
        $cartFinder = $this->findCart($cart->id());

        $this->assertEquals($cartFinder->id(), $cart->id());

        $this->deleteCart($cart->id());
    }

    public function testSaveCartItem(): void
    {
        $seller = SellerMother::create();
        $this->saveSeller($seller);

        $product = ProductMother::create(SellerId::fromString($seller->id()->toString()));
        $this->saveProduct($product);

        $cart = CartMother::create(new Items([]));
        $this->saveCart($cart);

        $item = ItemMother::create($product);
        $this->repository->saveItemCart($cart->id(), $item);

        $itemFinder = $this->findCartItem($cart->id(), $item);

        $this->assertEquals($product, $itemFinder->product());
        $this->assertEquals($item->quantity(), $itemFinder->quantity());

        $this->deleteItem($cart->id(), $product->id());
        $this->deleteCart($cart->id());
        $this->deleteProduct($product->id());
        $this->deleteSeller($seller->id());
    }

    public function testRemoveCartItem(): void
    {
        $seller = SellerMother::create();
        $this->saveSeller($seller);

        $product = ProductMother::create(SellerId::fromString($seller->id()->toString()));
        $this->saveProduct($product);

        $cart = CartMother::create(new Items([]));
        $this->saveCart($cart);

        $item = ItemMother::create($product);
        $this->saveItem($cart->id(), $item);

        $this->repository->removeItemCart($cart->id(), $product->id());
        $itemDelete = $this->findCartItem($cart->id(), $item);

        $this->assertNull($itemDelete);

        $this->deleteCart($cart->id());
        $this->deleteProduct($product->id());
        $this->deleteSeller($seller->id());


    }

    /*public function testConfirmedCart(): void
    {
        $id = Uuid::uuid4()->toString();
        $cart = new Cart(
            CartId::fromString($id),
            Items::create([])
        );

        $this->createCart($cart);
        $cartNotConfirmed = $this->findCart($cart->id());
        $this->assertFalse($cartNotConfirmed->confirmed());

        $this->repository->cartConfirmed($cart->id());
        $cartConfirmed = $this->findCart($cart->id());
        $this->assertTrue($cartConfirmed->confirmed());

        $this->deleteCart($cart->id());
    }*/

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
            Items::create([]),
            $cart['confirmed']
        );
    }

    private function saveCart(Cart $cart): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "INSERT INTO cart(id) VALUES (:id)",
            [
                'id' => $cart->id()->toString(),
            ]
        );
        $this->connection->commit();
    }

    private function deleteCart(CartId $id): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "DELETE FROM cart WHERE id = :id",
            [
                'id' => $id->toString(),
            ]
        );
        $this->connection->commit();
    }

    private function findCartItem(CartId $cartId, Item $item): ?Item
    {
        $item = $this->connection->createQueryBuilder()
                                 ->select('*')
                                 ->from('cart_item', 'ci')
                                 ->join('ci', 'product', 'p', 'ci.product_id = p.id')
                                 ->where('ci.cart_id = :cart_id')
                                 ->andWhere('ci.product_id = :product_id')
                                 ->setParameter('cart_id', $cartId->toString())
                                 ->setParameter('product_id', $item->product()->id()->toString())
                                 ->executeQuery()->fetchAssociative();

        if(!$item) { return null; }
        return new Item(
            new Product(
                ProductId::fromString($item['product_id']),
                SellerId::fromString($item['seller_id']),
                Name::fromString($item['name']),
                Price::fromString($item['price'])
            ),
            Quantity::fromInt($item['quantity'])
        );
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

    private function saveItem(CartId $cartId, Item $item): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "INSERT INTO cart_item (cart_id, product_id, quantity)
                     VALUE (:cart_id, :product_id, :quantity)",
            [
                'cart_id' => $cartId->toString(),
                'product_id' => $item->product()->id()->toString(),
                'quantity' => $item->quantity()->toInt(),
            ]
        );
        $this->connection->commit();
    }

    private function deleteItem(CartId $cartId, ProductId $productId): void
    {
        $this->connection->beginTransaction();
        $this->connection->executeStatement(
            "DELETE FROM cart_item WHERE cart_id = :cart_id and product_id = :product_id",
            [
                'cart_id' => $cartId->toString(),
                'product_id' => $productId->toString(),
            ]
        );
        $this->connection->commit();
    }

    private function deleteProduct(ProductId $id): void
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

    private function deleteSeller(\App\ShoppingCart\Seller\Domain\SellerId $id): void
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