<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

final class RepositoryClassSubscriber extends AbstractDoctrineSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $this->setCustomRepositoryClass($eventArgs->getClassMetadata());
    }
    private function setCustomRepositoryClass(ClassMetadata $metadata): void
    {
        try {
            $resourceMetadata = $this->resourceRegistry->getByClass($metadata->getName());
        } catch (\InvalidArgumentException $exception) {
            return;
        }
        if ($resourceMetadata->hasClass('repository')) {
            $metadata->setCustomRepositoryClass($resourceMetadata->getClass('repository'));
        }
    }
}