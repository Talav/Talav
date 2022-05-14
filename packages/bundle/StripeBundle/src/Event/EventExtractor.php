<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Event;

use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Request;
use Talav\StripeBundle\Exception\StripeException;

class EventExtractor
{
    public function __construct(
        private ?string $webHookSecret
    ) {
    }

    /**
     * Extract stripe event from request.
     *
     * @throws StripeException
     */
    public function extract(Request $request): Event
    {
        try {
            // Secure webhook with event signature: https://stripe.com/docs/webhooks/signatures
            if (null !== $this->webHookSecret) {
                $sigHeader = $request->headers->get('Stripe-Signature');

                return Webhook::constructEvent(
                    $request->getContent(),
                    $sigHeader,
                    $this->webHookSecret
                );
            } else {
                return $this->constructEvent($request->getContent());
            }
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            throw new StripeException('Invalid event payload');
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            throw new StripeException('Invalid event signature');
        }
    }

    /**
     * Try to construct Stripe event from provided payload.
     *
     * @throws StripeException
     */
    private function constructEvent(string $payload): Event
    {
        $data = json_decode($payload, true);
        $jsonError = json_last_error();
        if (null === $data && JSON_ERROR_NONE !== $jsonError) {
            throw new StripeException("Invalid payload: $payload (json_last_error() was $jsonError)");
        }

        return Event::constructFrom($data);
    }
}
