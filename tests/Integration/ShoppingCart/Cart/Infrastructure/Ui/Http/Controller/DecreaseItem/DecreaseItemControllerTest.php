<?php

namespace App\Tests\Integration\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\DecreaseItem;

use App\ShoppingCart\Cart\Application\Item\ItemDecrement;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class DecreaseItemControllerTest extends WebTestCase
{
    private ItemDecrement|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(ItemDecrement::class);
        static::getContainer()->set('App\ShoppingCart\Cart\Application\Item\ItemDecrement', $this->useCase);
    }

    public function testDecreaseItem(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/item/decrease?cart_id=7d135250-3a2b-42e0-8956-2ccc24b03f01&product_id=ad46e828-2d2e-4674-946d-7fcefd66efec',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Item decrease successfully', json_decode($response->getContent(), true));
    }

    public function testDecreaseItemWithOutParamas(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/item/decrease',
        );

        $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}