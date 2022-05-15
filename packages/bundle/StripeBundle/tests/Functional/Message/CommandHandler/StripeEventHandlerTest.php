<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Tests\Message\CommandHandler;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Stripe\Event;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\StripeBundle\Entity\Price;
use Talav\StripeBundle\Entity\Product;
use Talav\StripeBundle\Event\EventExtractor;
use Talav\StripeBundle\Message\Command\StripeEventCommand;
use Talav\StripeBundle\Message\CommandHandler\StripeEventHandler;
use Talav\StripeBundle\Tests\Helper\FileContentRequest;

final class StripeEventHandlerTest extends KernelTestCase
{
    use FileContentRequest;

    private ManagerInterface $productManager;

    public function setUp(): void
    {
        static::getContainer()->get(DatabaseToolCollection::class)->get()->loadFixtures();
        $this->productManager = static::getContainer()->get('app.manager.product');
    }

    /**
     * @test
     */
    public function it_maps_product_event_to_new_product_entity()
    {
        $handler = self::getContainer()->get(StripeEventHandler::class);
        /** @var Product $entity */
        $entity = $handler->__invoke(new StripeEventCommand($this->getEvent('product.created')));
        $this->assertEquals('prod_Leyyy3KhUWphiQ', $entity->getId());
        $this->assertTrue($entity->isActive());
        $this->assertNotNull($entity->getCreated());
        $this->assertNotNull($entity->getUpdated());
        $this->assertFalse($entity->isLivemode());
        $this->assertEquals(['meta 1' => 'value 1'], $entity->getMetadata());
        $this->assertEquals('Premium plan', $entity->getName());
    }

    /**
     * @test
     */
    public function it_maps_price_event_to_new_price_entity_and_links_to_product()
    {
        $handler = self::getContainer()->get(StripeEventHandler::class);
        $handler->__invoke(new StripeEventCommand($this->getEvent('product.created')));
        /** @var Price $entity */
        $entity = $handler->__invoke(new StripeEventCommand($this->getEvent('price.created')));
        $product = $entity->getProduct();
        $this->assertNotNull($product);
        $this->productManager->reload($product);
        $this->assertCount(1, $product->getPrices());
    }

    private function getEvent($event): Event
    {
        $extractor = new EventExtractor(null);

        return $extractor->extract($this->getEventFromFile($event));
    }
}
