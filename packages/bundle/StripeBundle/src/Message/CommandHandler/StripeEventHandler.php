<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Message\CommandHandler;

use AutoMapperPlus\AutoMapperInterface;
use Stripe\Event;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Talav\Component\Registry\Registry\ServiceRegistryInterface;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\StripeBundle\Message\Command\StripeEventCommand;

final class StripeEventHandler implements MessageHandlerInterface
{
    public function __construct(
        private AutoMapperInterface $mapper,
        private ServiceRegistryInterface $managerRegistry,
        private readonly array $classMap
    ) {
    }

    public function __invoke(StripeEventCommand $message): ResourceInterface
    {
        $stdObject = $this->getDataObject($message->event);
        $manager = $this->managerRegistry->get($this->classMap[$stdObject->object]);
        $entity = $manager->getRepository()->find($stdObject->id);
        if (is_null($entity)) {
            $entity = $manager->create();
        }
        $this->mapper->mapToObject($stdObject, $entity);
        $manager->update($entity, true);

        return $entity;
    }

    /**
     * Stripe event to supported std object.
     */
    private function getDataObject(Event $stripeEvent): \stdClass
    {
        if (!$this->isSupported($stripeEvent)) {
            throw new \RuntimeException('Event is not supported');
        }

        return (object) $stripeEvent->data->object->toArray();
    }

    /**
     * Define if mapper supports this stripe event.
     */
    private function isSupported(Event $stripeEvent): bool
    {
        return isset($this->classMap[$stripeEvent->data->object->object]);
    }
}
