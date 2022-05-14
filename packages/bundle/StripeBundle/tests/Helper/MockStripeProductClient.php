<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Tests\Helper;

use Stripe\HttpClient\ClientInterface;

class MockStripeProductClient implements ClientInterface
{
    use FileContentRequest;

    public function request($method, $absUrl, $headers, $params, $hasFile)
    {
        // create
        if ('https://api.stripe.com/v1/products' == $absUrl) {
            return [$this->loadJsonFile('response', 'product.create'), 200, []];
        }
        // update
        return [$this->loadJsonFile('response', 'product.update'), 200, []];
    }
}
