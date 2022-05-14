<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Event;

use Stripe\StripeObject;
use Talav\StripeBundle\Exception\StripeException;

class StripeEvent
{
    public const CHARGE_CAPTURED = 'stripe.charge.captured';
    public const CHARGE_FAILED = 'stripe.charge.failed';
    public const CHARGE_PENDING = 'stripe.charge.pending';
    public const CHARGE_REFUNDED = 'stripe.charge.refunded';
    public const CHARGE_SUCCEEDED = 'stripe.charge.succeeded';
    public const CHARGE_UPDATED = 'stripe.charge.updated';
    public const COUPON_CREATED = 'stripe.coupon.created';
    public const COUPON_DELETED = 'stripe.coupon.deleted';
    public const COUPON_UPDATED = 'stripe.coupon.updated';
    public const CUSTOMER_CREATED = 'stripe.customer.created';
    public const CUSTOMER_DELETED = 'stripe.customer.deleted';
    public const CUSTOMER_UPDATED = 'stripe.customer.updated';
    public const CUSTOMER_DISCOUNT_CREATED = 'stripe.customer.discount.created';
    public const CUSTOMER_DISCOUNT_DELETED = 'stripe.customer.discount.deleted';
    public const CUSTOMER_DISCOUNT_UPDATED = 'stripe.customer.discount.updated';
    public const CUSTOMER_SOURCE_CREATED = 'stripe.customer.source.created';
    public const CUSTOMER_SOURCE_DELETED = 'stripe.customer.source.deleted';
    public const CUSTOMER_SOURCE_UPDATED = 'stripe.customer.source.updated';
    public const CUSTOMER_SUBSCRIPTION_CREATED = 'stripe.customer.subscription.created';
    public const CUSTOMER_SUBSCRIPTION_DELETED = 'stripe.customer.subscription.deleted';
    public const CUSTOMER_SUBSCRIPTION_UPDATED = 'stripe.customer.subscription.updated';
    public const CUSTOMER_SUBSCRIPTION_TRAIL_WILL_END = 'stripe.customer.subscription.trial_will_end';
    public const INVOICE_CREATED = 'stripe.invoice.created';
    public const INVOICE_PAYMENT_FAILED = 'stripe.invoice.payment_failed';
    public const INVOICE_PAYMENT_SUCCEEDED = 'stripe.invoice.payment_succeeded';
    public const INVOICE_SENT = 'stripe.invoice.sent';
    public const INVOICE_UPCOMING = 'stripe.invoice.upcoming';
    public const INVOICE_UPDATED = 'stripe.invoice.updated';
    public const INVOICE_DELETED = 'stripe.invoice.deleted';
    public const INVOICE_FINALIZED = 'stripe.invoice.finalized';
    public const PLAN_CREATED = 'stripe.plan.created';
    public const PLAN_DELETED = 'stripe.plan.deleted';
    public const PLAN_UPDATED = 'stripe.plan.updated';
    public const SOURCE_CANCELED = 'stripe.source.canceled';
    public const SOURCE_CHARGEABLE = 'stripe.source.chargeable';
    public const SOURCE_FAILED = 'stripe.source.failed';

    /**
     * @var StripeObject
     */
    protected $event;

    /**
     * StripeEventType constructor.
     *
     * @param StripeObject $event
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get stripe event object.
     *
     * @return StripeObject
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get event data object.
     *
     * @return StripeObject
     *
     * @throws StripeException
     */
    public function getDataObject()
    {
        $event = $this->getEvent();
        if (!isset($event['data']) || !isset($event['data']['object'])) {
            throw new StripeException('Invalid event data');
        }

        return $event['data']['object'];
    }
}
