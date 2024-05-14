<?php

namespace App\Tests\Integration\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\RemoveSeller;

use App\ShoppingCart\Seller\Application\SellerEliminator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class RemoveSellerControllerTest extends WebTestCase
{
    private SellerEliminator|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(SellerEliminator::class);
        static::getContainer()->set('App\ShoppingCart\Seller\Application\SellerEliminator', $this->useCase);
    }

    public function testRemoveSeller(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/seller/remove?id=d2c1b150-7c45-4125-bc37-a8da3bf594be',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Seller is remove successfully', json_decode($response->getContent(), true));
    }

    public function testRemoveSellerWithoutParams(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/seller/remove',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}