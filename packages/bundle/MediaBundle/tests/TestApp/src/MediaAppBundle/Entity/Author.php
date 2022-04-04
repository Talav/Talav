<?php

declare(strict_types=1);

namespace MediaAppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Talav\Component\Media\Model\MediaInterface;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\Resource\Model\Timestampable;

#[Entity]
#[Table(name: 'test_author')]
class Author implements ResourceInterface
{
    use ResourceTrait;
    use Timestampable;

    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue(strategy: 'AUTO')]
    protected $id = null;

    #[Column(type: 'string')]
    protected ?string $name = null;

    #[OneToOne(targetEntity: "Talav\Component\Media\Model\MediaInterface", cascade: ['persist'])]
    #[JoinColumn(name: 'media_id')]
    protected ?MediaInterface $media = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getMedia(): ?MediaInterface
    {
        return $this->media;
    }

    public function setMedia(?MediaInterface $media): void
    {
        $this->media = $media;
    }
}
