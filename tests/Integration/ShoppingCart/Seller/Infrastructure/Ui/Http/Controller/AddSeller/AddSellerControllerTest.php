<?php

namespace App\Tests\Integration\ShoppingCart\Seller\Infrastructure\Ui\Http\Controller\AddSeller;

use App\ShoppingCart\Seller\Application\SellerCreator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class AddSellerControllerTest extends WebTestCase
{
    private SellerCreator|MockObject $useCase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->useCase = $this->createMock(SellerCreator::class);
        static::getContainer()->set('App\ShoppingCart\Seller\Application\SellerCreator', $this->useCase);
    }

    public function testAddSeller(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/seller/add?name=test',
        );

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('Seller is saved successfully', json_decode($response->getContent(), true));
    }
}