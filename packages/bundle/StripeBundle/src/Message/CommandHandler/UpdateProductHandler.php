<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Message\CommandHandler;

use AutoMapperPlus\AutoMapperInterface;
use Stripe\StripeClient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\StripeBundle\Message\Command\UpdateProductCommand;

final class UpdateProductHandler implements MessageHandlerInterface
{
    public function __construct(
        private AutoMapperInterface $mapper,
        private ManagerInterface $productManager,
        private StripeClient $stripeClient
    ) {
    }

    public function __invoke(UpdateProductCommand $message)
    {
        $data = (array) $this->mapper->map($message->dto, \stdClass::class);
        $stripeProduct = $this->stripeClient->products->update($message->product->getId(), $data);
        $this->mapper->mapToObject((object) $stripeProduct->toArray(), $message->product);
        $this->productManager->update($message->product, true);

        return $message->product;
    }
}
