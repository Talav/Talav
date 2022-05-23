<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Model;

trait Sluggable
{
    protected ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
