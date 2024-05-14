<?php

namespace App\Tests\Integration\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\RemoveProduct;

use App\ShoppingCart\Product\Application\ProductEliminator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class RemoveProductControllerTest extends WebTestCase
{
    private ProductEliminator|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(ProductEliminator::class);
        static::getContainer()->set('App\ShoppingCart\Product\Application\ProductEliminator', $this->useCase);
    }

    public function testRemoveProduct(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/product/remove?id=cd8250a7-779c-4a95-b6e0-7f47a8134f7d',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Product is remove successfully', json_decode($response->getContent(), true));
    }

    public function testAddProductWithoutParams(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/product/remove',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}