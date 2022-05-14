<?php

namespace Talav\StripeBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Talav\StripeBundle\Event\EventExtractor;
use Talav\StripeBundle\Message\Command\StripeEventCommand;

class WebhookController extends AbstractController
{
    public function __construct(
        private EventExtractor $eventExtractor,
        private MessageBusInterface $bus
    ) {
    }

    /**
     * @Route("/stripe/webhook", name="talav_stripe_webhook")
     */
    public function handleAction(Request $request)
    {
        $this->bus->dispatch(new StripeEventCommand($this->eventExtractor->extract($request)));

        return new Response();
    }
}
