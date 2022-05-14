<?php

declare(strict_types=1);

namespace Talav\Component\Subscription\Model;

class Subscription
{
    protected Product $product;

    public function getProduct(): Product
    {
        return $this->product;
    }
}
