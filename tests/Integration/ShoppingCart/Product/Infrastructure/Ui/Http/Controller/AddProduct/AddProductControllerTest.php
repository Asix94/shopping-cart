<?php

namespace App\Tests\Integration\ShoppingCart\Product\Infrastructure\Ui\Http\Controller\AddProduct;

use App\ShoppingCart\Product\Application\ProductCreator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class AddProductControllerTest extends WebTestCase
{
    private ProductCreator|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(ProductCreator::class);
        static::getContainer()->set('App\ShoppingCart\Product\Application\ProductCreator', $this->useCase);
    }

    public function testAddProduct(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/product/add?sellerId=cd8250a7-779c-4a95-b6e0-7f47a8134f7d&name=test&price=20',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Product is saved successfully', json_decode($response->getContent(), true));
    }

    public function testAddProductWithoutParams(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/product/add',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}