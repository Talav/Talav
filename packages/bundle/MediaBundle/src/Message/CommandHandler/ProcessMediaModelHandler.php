<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Message\CommandHandler;

use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Talav\Component\Media\Message\Command\Media\CreateMediaCommand;
use Talav\Component\Media\Message\Command\Media\DeleteMediaCommand;
use Talav\Component\Media\Message\Command\Media\UpdateMediaCommand;
use Talav\Component\Media\Message\Dto\Media\CreateMediaDto;
use Talav\Component\Media\Message\Dto\Media\UpdateMediaDto;
use Talav\MediaBundle\Message\Command\ProcessMediaModelCommand;

final class ProcessMediaModelHandler implements MessageHandlerInterface
{
    private AutoMapperInterface $mapper;

    private MessageBusInterface $messageBus;

    public function __construct(
        AutoMapperInterface $mapper,
        MessageBusInterface $messageBus
    ) {
        $this->mapper = $mapper;
        $this->messageBus = $messageBus;
    }

    public function __invoke(ProcessMediaModelCommand $message)
    {
        if (!$message->isActionable()) {
            return null;
        }
        if ($message->isCreate()) {
            return $this->messageBus->dispatch(new CreateMediaCommand($this->mapper->map($message->getDto(), CreateMediaDto::class)))
                ->last(HandledStamp::class)
                ->getResult();
        }
        if ($message->isUpdate()) {
            return $this->messageBus->dispatch(new UpdateMediaCommand($message->getDto()->media, $this->mapper->map($message->getDto(), UpdateMediaDto::class)))
                ->last(HandledStamp::class)
                ->getResult();
        }

        if ($message->isDelete()) {
            $this->messageBus->dispatch(new DeleteMediaCommand($message->getDto()->media));
        }

        return null;
    }
}
