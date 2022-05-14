<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Message\CommandHandler;

use AutoMapperPlus\AutoMapperInterface;
use Stripe\StripeClient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\StripeBundle\Message\Command\CreateProductCommand;
use Talav\StripeBundle\Message\Event\NewProductEvent;

final class CreateProductHandler implements MessageHandlerInterface
{
    public function __construct(
        private AutoMapperInterface $mapper,
        private ManagerInterface $productManager,
        private MessageBusInterface $messageBus,
        private StripeClient $stripeClient
    ) {
    }

    public function __invoke(CreateProductCommand $message)
    {
        $stripeProduct = $this->stripeClient->products->create((array) $this->mapper->map($message->dto, \stdClass::class));
        $product = $this->productManager->create();
        $this->mapper->mapToObject((object) $stripeProduct->toArray(), $product);
        $this->productManager->update($product, true);

        $this->messageBus->dispatch(new NewProductEvent($product->getId()));

        return $product;
    }
}
