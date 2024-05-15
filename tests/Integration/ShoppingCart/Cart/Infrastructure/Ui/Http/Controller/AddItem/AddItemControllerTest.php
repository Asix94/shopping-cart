<?php

namespace App\Tests\Integration\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\AddItem;

use App\ShoppingCart\Cart\Application\Item\ItemCreator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class AddItemControllerTest extends WebTestCase
{
    private ItemCreator|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(ItemCreator::class);
        static::getContainer()->set('App\ShoppingCart\Cart\Application\Item\ItemCreator', $this->useCase);
    }

    public function testAddItem(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/item/add?cart_id=7d135250-3a2b-42e0-8956-2ccc24b03f01&product_id=ad46e828-2d2e-4674-946d-7fcefd66efec',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Item is saved successfully', json_decode($response->getContent(), true));
    }

    public function testAddItemWithOutParams(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/item/add',
        );

        $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}