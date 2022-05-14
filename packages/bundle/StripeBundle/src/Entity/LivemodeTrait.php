<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

trait LivemodeTrait
{
    /**
     * Has the value true if the object exists in live mode or the value false if the object exists in test mode.
     */
    protected bool $livemode = false;

    public function isLivemode(): bool
    {
        return $this->livemode;
    }

    public function setLivemode(bool $livemode): void
    {
        $this->livemode = $livemode;
    }
}
