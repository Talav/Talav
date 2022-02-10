<?php

declare(strict_types=1);

namespace Talav\Component\Media\Provider;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Talav\Component\Media\Cdn\CdnInterface;
use Talav\Component\Media\Exception\InvalidMediaException;
use Talav\Component\Media\Generator\GeneratorInterface;
use Talav\Component\Media\Model\MediaInterface;

class FileProvider implements MediaProviderInterface
{
    protected string $name;

    protected FilesystemOperator $filesystem;

    protected ValidatorInterface $validator;

    protected CdnInterface $cdn;

    protected GeneratorInterface $generator;

    protected Constraints $constrains;

    protected array $formats = [];

    /** @var MediaInterface[] */
    protected array $clones = [];

    public function __construct(
        string $name,
        FilesystemOperator $filesystem,
        CdnInterface $cdn,
        GeneratorInterface $generator,
        ValidatorInterface $validator,
        ?Constraints $constrains = null
    ) {
        $this->name = $name;
        $this->filesystem = $filesystem;
        $this->validator = $validator;
        $this->cdn = $cdn;
        $this->generator = $generator;
        if (null === $constrains) {
            $constrains = new Constraints([], []);
        }
        $this->constrains = $constrains;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function prePersist(MediaInterface $media): void
    {
        // validate media file
        $this->validateMedia($media);
    }

    public function preUpdate(MediaInterface $media): void
    {
        // validate media file
        $this->validateMedia($media);
    }

    public function preRemove(MediaInterface $media): void
    {
        // clone media to process files in postRemove
        $hash = spl_object_hash($media);
        $this->clones[$hash] = clone $media;
    }

    public function postUpdate(MediaInterface $media): void
    {
        if (null === $media->getFile()) {
            return;
        }

        // Delete the current file from the FS
        $oldMedia = clone $media;
        // if no previous reference is provided, it prevents
        // Filesystem from trying to remove a directory
        if (null !== $media->getPreviousProviderReference()) {
            $oldMedia->setProviderReference($media->getPreviousProviderReference());
            $this->deletePath($this->getFilesystemReference($oldMedia));
        }
        $this->copyTempFile($media);
        $media->resetFile();
    }

    public function postRemove(MediaInterface $media): void
    {
        $hash = spl_object_hash($media);

        if (isset($this->clones[$hash])) {
            $media = $this->clones[$hash];
            unset($this->clones[$hash]);
        }
        $this->deletePath($this->getFilesystemReference($media));
    }

    public function postPersist(MediaInterface $media): void
    {
        if (null === $media->getFile()) {
            return;
        }
        $this->copyTempFile($media);
        $media->resetFile();
    }

    public function getFilesystem(): FilesystemOperator
    {
        return $this->filesystem;
    }

    public function generatePath(MediaInterface $media): string
    {
        return $this->generator->generatePath($media);
    }

    public function getFilesystemReference(MediaInterface $media): string
    {
        return sprintf('%s/%s', $this->generatePath($media), $media->getProviderReference());
    }

    public function getMediaContent(MediaInterface $media): string
    {
        return $this->getFilesystem()->read($this->getFilesystemReference($media));
    }

    public function getFileFieldConstraints(): array
    {
        return $this->constrains->getFieldConstraints();
    }

    public function addFormat(string $name, array $options): void
    {
        $this->formats[$name] = $options;
    }

    public function getFormat($name): array
    {
        if (!isset($this->formats[$name])) {
            throw new \RuntimeException('Format is not found');
        }

        return $this->formats[$name];
    }

    public function getFormats(): array
    {
        return $this->formats;
    }

    /**
     * Set the file contents for an media.
     */
    protected function copyTempFile(MediaInterface $media): void
    {
        $this->getFilesystem()->write(
            $this->getFilesystemReference($media),
            file_get_contents($media->getFile()->getRealPath())
        );
    }

    /**
     * Delete file if it exists.
     */
    protected function deletePath(string $path): void
    {
        if ($this->getFilesystem()->fileExists($path)) {
            $this->getFilesystem()->delete($path);
        }
    }

    /**
     * Make sure media is valid.
     */
    protected function validateMedia(MediaInterface $media): void
    {
        if (empty($media->getContext())) {
            throw new InvalidMediaException('Media should have context defined');
        }
        if (empty($media->getProviderName())) {
            throw new InvalidMediaException('Media should have provider defined');
        }
        if (empty($media->getName())) {
            throw new InvalidMediaException('Media should have name defined');
        }
        $violations = $this->validator->validate($media->getFile(), $this->constrains->getFieldConstraints());
        if (0 < $violations->count()) {
            throw new InvalidMediaException('Invalid media file');
        }
    }
}
