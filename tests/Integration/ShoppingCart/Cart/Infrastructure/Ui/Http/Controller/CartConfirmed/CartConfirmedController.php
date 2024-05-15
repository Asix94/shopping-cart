<?php

namespace App\Tests\Integration\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\CartConfirmed;

use App\ShoppingCart\Cart\Application\Cart\CartConfirmed;
use App\ShoppingCart\Cart\Application\Item\ItemsEliminator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CartConfirmedController extends WebTestCase
{
    private CartConfirmed|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(CartConfirmed::class);
        static::getContainer()->set('App\ShoppingCart\Cart\Application\Cart\CartConfirmed', $this->useCase);
    }

    public function testConfirmedCart(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/confirmed?cart_id=7d135250-3a2b-42e0-8956-2ccc24b03f01',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Cart is confirmed successfully', json_decode($response->getContent(), true));
    }

    public function testConfirmedCartWithOutParams(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/confirmed',
        );

        $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}