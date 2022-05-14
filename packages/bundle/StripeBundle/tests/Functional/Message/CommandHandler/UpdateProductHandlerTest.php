<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Tests\Message\CommandHandler;

use AutoMapperPlus\AutoMapperInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Stripe\ApiRequestor;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Talav\StripeBundle\Entity\Product;
use Talav\StripeBundle\Message\Command\CreateProductCommand;
use Talav\StripeBundle\Message\Command\UpdateProductCommand;
use Talav\StripeBundle\Message\CommandHandler\CreateProductHandler;
use Talav\StripeBundle\Message\CommandHandler\UpdateProductHandler;
use Talav\StripeBundle\Message\Dto\CreateProductDto;
use Talav\StripeBundle\Message\Dto\UpdateProductDto;
use Talav\StripeBundle\Tests\Helper\MockStripeProductClient;

final class UpdateProductHandlerTest extends KernelTestCase
{
    public function setUp(): void
    {
        static::getContainer()->get(DatabaseToolCollection::class)->get()->loadFixtures();
        ApiRequestor::setHttpClient(new MockStripeProductClient());
    }

    /**
     * @test
     */
    public function it_updates_stripe_product_and_entity()
    {
        $product = $this->createProduct();
        $productNameUpdated = 'Premium plan updated';
        $command = new UpdateProductCommand($product, new UpdateProductDto(
            name: $productNameUpdated,
        ));
        $handler = new UpdateProductHandler(
            static::getContainer()->get(AutoMapperInterface::class),
            static::getContainer()->get('app.manager.product'),
            static::getContainer()->get(StripeClient::class),
        );
        $handler->__invoke($command);
        $product = static::getContainer()->get('app.manager.product')->getRepository()->findOneBy(['name' => $productNameUpdated]);
        self::assertNotNull($product);
        self::assertNotEquals($product->getCreated(), $product->getUpdated());
    }

    public function createProduct(): Product
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

        return static::getContainer()->get('app.manager.product')->getRepository()->findOneBy(['name' => $productName]);
    }
}
