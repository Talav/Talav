<?php

namespace Talav\Component\Media\Tests\Functional\Setup\Entity;

use Doctrine\ORM\Mapping as ORM;
use Talav\Component\Media\Model\Media;

/**
 * Doctrine media entity that is used for testing.
 *
 * @ORM\Entity(repositoryClass="Talav\Component\Resource\Repository\ResourceRepository")
 * @ORM\Table(name="test_media")
 */
class MediaEntity extends Media
{
    /**
     * A unique ID.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue
     */
    public $id = null;

    /**
     * @ORM\Column(type="string", name="name", nullable=true)
     */
    public ?string $name = null;

    /**
     * @ORM\Column(type="string", name="description", nullable=true)
     */
    protected ?string $description = null;

    /**
     * @ORM\Column(type="string", name="context", nullable=true)
     */
    protected ?string $context = null;

    /**
     * @ORM\Column(type="string", name="provider_name", nullable=true)
     */
    protected ?string $providerName = null;

    /**
     * @var int
     * @ORM\Column(type="integer", name="provider_status", nullable=true)
     */
    protected $providerStatus;

    /**
     * @ORM\Column(type="string", name="provider_reference", nullable=true)
     */
    protected ?string $providerReference = null;

    /**
     * @ORM\Column(type="integer", name="size", nullable=true)
     */
    protected ?int $size = null;

    /**
     * Mime type of the new file.
     *
     * @ORM\Column(type="string", name="mime_type", nullable=true)
     */
    protected ?string $mimeType = null;

    /**
     * File extension.
     *
     * @ORM\Column(type="string", name="file_extension", nullable=true)
     */
    protected ?string $fileExtension;

    /**
     * File extension.
     *
     * @ORM\Column(type="string", name="file_name", nullable=true)
     */
    protected ?string $fileName;

    /**
     * @ORM\Column(type="json", name="thumbs_info", nullable=false)
     */
    protected ?array $thumbsInfo = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
