<?php

namespace Talav\Component\Resource\Tests\Functional\Setup\Entity;

use Doctrine\ORM\Mapping as ORM;
use Talav\Component\Resource\Model\ResourceInterface;

/**
 * Doctrine entity that is used for testing.
 *
 * @ORM\Entity(repositoryClass="Talav\Component\Resource\Repository\ResourceRepository")
 * @ORM\Table(name="test_entity")
 */
class TestEntity implements ResourceInterface
{
    /**
     * A unique ID.
     *
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue
     */
    public $id = null;

    /**
     * @var string
     * @ORM\Column(type="string", name="name", nullable=true)
     */
    public $name = null;

    /**
     * @var string
     * @ORM\Column(type="string", name="title", nullable=true)
     */
    public $title = null;

    public function getId()
    {
        return $this->id;
    }
}