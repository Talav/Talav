<?php

declare(strict_types=1);

namespace MediaAppBundle\Entity;

use Talav\Component\Media\Model\MediaInterface;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\Resource\Model\Timestampable;

class Author implements ResourceInterface
{
    use ResourceTrait;
    use Timestampable;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var MediaInterface|null
     */
    protected $media;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return MediaInterface|null
     */
    public function getMedia(): ?MediaInterface
    {
        return $this->media;
    }

    /**
     * @param MediaInterface|null $media
     */
    public function setMedia(?MediaInterface $media): void
    {
        $this->media = $media;
    }
}