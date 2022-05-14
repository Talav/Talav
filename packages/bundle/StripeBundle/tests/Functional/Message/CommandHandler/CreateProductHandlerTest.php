<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Tests\Message\CommandHandler;

use AutoMapperPlus\AutoMapperInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Stripe\ApiRequestor;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Talav\StripeBundle\Message\Command\CreateProductCommand;
use Talav\StripeBundle\Message\CommandHandler\CreateProductHandler;
use Talav\StripeBundle\Message\Dto\CreateProductDto;
use Talav\StripeBundle\Tests\Helper\MockStripeProductClient;

final class CreateProductHandlerTest extends KernelTestCase
{
    public function setUp(): void
    {
        static::getContainer()->get(DatabaseToolCollection::class)->get()->loadFixtures();
        ApiRequestor::setHttpClient(new MockStripeProductClient());
    }

    /**
     * @test
     */
    public function it_creates_stripe_product_and_product_entity()
    {
        $productName = 'Premium plan';
        $productDescription = 'my premium plan';
        $command = new CreateProductCommand(new CreateProductDto(
            name: $productName,
            metadata: ['meta 1' => 'value 1'],
            description: $productDescription
        ));
        $handler = new CreateProductHandler(
            static::getContainer()->get(AutoMapperInterface::class),
            static::getContainer()->get('app.manager.product'),
            static::getContainer()->get(MessageBusInterface::class),
            static::getContainer()->get(StripeClient::class),
        );
        $handler->__invoke($command);
        $product = static::getContainer()->get('app.manager.product')->getRepository()->findOneBy(['name' => $productName]);
        self::assertNotNull($product);
        self::assertEquals($productDescription, $product->getDescription());
        self::assertEquals($product->getCreated(), $product->getUpdated());
        self::assertStringContainsString('prod_', $product->getId(), 'Product id should match stripe ID');
    }
}
