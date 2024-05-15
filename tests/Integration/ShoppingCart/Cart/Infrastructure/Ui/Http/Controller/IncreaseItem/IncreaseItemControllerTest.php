<?php

namespace App\Tests\Integration\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\IncreaseItem;

use App\ShoppingCart\Cart\Application\Item\ItemIncrementor;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class IncreaseItemControllerTest extends WebTestCase
{
    private ItemIncrementor|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(ItemIncrementor::class);
        static::getContainer()->set('App\ShoppingCart\Cart\Application\Item\ItemIncrementor', $this->useCase);
    }

    public function testIncrementItem(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/item/increment?cart_id=7d135250-3a2b-42e0-8956-2ccc24b03f01&product_id=ad46e828-2d2e-4674-946d-7fcefd66efec',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Item increase successfully', json_decode($response->getContent(), true));
    }

    public function testIncrementItemWithOutParamas(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/item/increment',
        );

        $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}