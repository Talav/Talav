<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Message\Command;

use Talav\Component\Resource\Model\DomainEventInterface;
use Talav\MediaBundle\Entity\Media;
use Talav\MediaBundle\Message\Dto\ProcessMediaDto;

final class ProcessMediaModelCommand implements DomainEventInterface
{
    private ProcessMediaDto $dto;

    public function __construct(ProcessMediaDto $dto)
    {
        $this->dto = $dto;
    }

    public function getDto(): ProcessMediaDto
    {
        return $this->dto;
    }

    // Command is not actionable if it does not provide sufficient information
    // There is no possible action If existing media does not exist and there is no file for a new media
    public function isActionable(): bool
    {
        return !is_null($this->dto->media) || !is_null($this->dto->formModel->file);
    }

    // There is no previous media for command
    public function isCreate(): bool
    {
        return !is_null($this->dto->formModel->file) && is_null($this->dto->media);
    }

    // There is a previous media and a new file
    public function isUpdate(): bool
    {
        return !is_null($this->dto->media) && !$this->dto->formModel->unlink;
    }

    public function isUnlink(): bool
    {
        return !is_null($this->dto->media) && $this->dto->formModel->unlink;
    }

    public function isDelete(): bool
    {
        return $this->isUnlink() && $this->dto->formModel->delete;
    }
}
