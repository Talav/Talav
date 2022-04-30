<?php

declare(strict_types=1);

namespace Talav\Component\Media\Model;

use Symfony\Component\HttpFoundation\File\File;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\Resource\Model\Timestampable;

class Media implements MediaInterface
{
    use ResourceTrait;
    use Timestampable;

    protected ?string $name = null;

    protected ?string $description = null;

    protected ?string $context = null;

    protected ?string $providerName = null;

    protected ?string $providerReference = null;

    protected ?int $size = null;

    protected ?array $thumbsInfo = null;

    /**
     * Mime type of the new file.
     */
    protected ?string $mimeType = null;

    /**
     * File extension.
     */
    protected ?string $fileExtension = null;

    /**
     * File name.
     */
    protected ?string $fileName = null;

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): void
    {
        $this->context = $context;
    }

    public function getProviderName(): ?string
    {
        return $this->providerName;
    }

    public function setProviderName(?string $providerName): void
    {
        $this->providerName = $providerName;
    }

    public function getProviderReference(): ?string
    {
        // this is the name used to store the file
        if (is_null($this->providerReference)) {
            if (is_null($this->getFileExtension())) {
                $this->setProviderReference($this->generateReferenceName());
            } else {
                $this->setProviderReference(sprintf('%s.%s', $this->generateReferenceName(), $this->getFileExtension()));
            }
        }

        return $this->providerReference;
    }

    public function setProviderReference(?string $providerReference): void
    {
        $this->providerReference = $providerReference;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): void
    {
        $this->size = $size;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }

    public function setFileExtension(?string $fileExtension): void
    {
        $this->fileExtension = $fileExtension;
    }

    public function getThumbsInfo(): array
    {
        return $this->thumbsInfo;
    }

    public function setThumbsInfo(array $thumbsInfo): void
    {
        $this->thumbsInfo = $thumbsInfo;
    }

    protected function generateReferenceName(): string
    {
        return sha1($this->getName().uniqid().random_int(11111, 99999));
    }
}
