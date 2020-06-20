<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Model;

trait ResourceTrait
{
    /** @var mixed */
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return string representation of entity
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}
