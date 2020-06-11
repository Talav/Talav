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
}
