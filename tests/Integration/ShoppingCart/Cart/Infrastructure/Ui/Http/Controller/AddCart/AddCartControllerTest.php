<?php

namespace App\Tests\Integration\ShoppingCart\Cart\Infrastructure\Ui\Http\Controller\AddCart;

use App\ShoppingCart\Cart\Application\Cart\CartCreator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class AddCartControllerTest extends WebTestCase
{
    private CartCreator|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(CartCreator::class);
        static::getContainer()->set('App\ShoppingCart\Cart\Application\Cart\CartCreator', $this->useCase);
    }

    public function testAddProduct(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/cart/add',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Cart is saved successfully', json_decode($response->getContent(), true));
    }
}