<?php

namespace App\Tests\Integration\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\RemoveAllItems;

use App\ShoppingCart\Cart\Application\Item\ItemsEliminator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class RemoveAllItemControllerTest extends WebTestCase
{
    private ItemsEliminator|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(ItemsEliminator::class);
        static::getContainer()->set('App\ShoppingCart\Cart\Application\Item\ItemsEliminator', $this->useCase);
    }

    public function testRemoveItem(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/item/remove_all?cart_id=7d135250-3a2b-42e0-8956-2ccc24b03f01',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Items removed successfully', json_decode($response->getContent(), true));
    }

    public function testRemoveItemWithOutItems(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/item/remove_all',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}