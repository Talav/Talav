<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\StripeBundle\Enum\BillingSchema;
use Talav\StripeBundle\Enum\PriceTiersMode;
use Talav\StripeBundle\Enum\PriceType;
use Talav\StripeBundle\Enum\TaxBehavior;

class Price implements ResourceInterface, StripeObject, PriceInterface
{
    use ResourceTrait;
    use CreatedTrait;
    use UpdatedTrait;
    use ActiveTrait;
    use LivemodeTrait;
    use MetadataTrait;

    /**
     * Describes how to compute the price per period. Either per_unit or tiered.
     * per_unit indicates that the fixed amount (specified in unit_amount or unit_amount_decimal) will be charged per unit in quantity (for prices with usage_type=licensed),
     * or per unit of total usage (for prices with usage_type=metered).
     * tiered indicates that the unit pricing will be computed using a tiering strategy as defined using the tiers and tiers_mode attributes.
     */
    protected BillingSchema $billingScheme = BillingSchema::PER_UNIT;

    /**
     * Three-letter ISO currency code, in lowercase. Must be a supported currency.
     */
    protected ?string $currency = null;

    /**
     * A lookup key used to retrieve prices dynamically from a static string. This may be up to 200 characters.
     */
    protected ?string $lookupKey = null;

    /**
     * A brief description of the price, hidden from customers.
     */
    protected ?string $nickname = null;

    /**
     * The product this price is associated with.
     */
    protected ?Product $product = null;

    /**
     * The recurring components of a price such as interval and usage_type.
     */
    protected ?PriceRecurring $recurring = null;

    /**
     * Specifies whether the price is considered inclusive of taxes or exclusive of taxes.
     * One of inclusive, exclusive, or unspecified. Once specified as either inclusive or exclusive, it cannot be changed.
     */
    protected ?TaxBehavior $taxBehavior = null;

    /**
     * Defines if the tiering price should be graduated or volume based.
     * In volume-based tiering, the maximum quantity within a period determines the per unit price.
     * In graduated tiering, pricing can change as the quantity grows.
     */
    protected ?PriceTiersMode $tiersMode = null;

    /**
     * One of one_time or recurring depending on whether the price is for a one-time purchase or a recurring (subscription) purchase.
     */
    protected ?PriceType $type = null;

    /**
     * The unit amount in cents to be charged, represented as a whole integer if possible. Only set if billing_scheme=per_unit.
     */
    protected ?int $unitAmount = null;

    /**
     * The unit amount in cents to be charged, represented as a decimal string with at most 12 decimal places. Only set if billing_scheme=per_unit.
     */
    protected ?int $unitAmountDecimal = null;

    public function getBillingScheme(): BillingSchema
    {
        return $this->billingScheme;
    }

    public function setBillingScheme(BillingSchema $billingScheme): void
    {
        $this->billingScheme = $billingScheme;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getLookupKey(): ?string
    {
        return $this->lookupKey;
    }

    public function setLookupKey(?string $lookupKey): void
    {
        $this->lookupKey = $lookupKey;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    public function getRecurring(): ?PriceRecurring
    {
        return $this->recurring;
    }

    public function setRecurring(?PriceRecurring $recurring): void
    {
        $this->recurring = $recurring;
    }

    public function getTaxBehavior(): ?TaxBehavior
    {
        return $this->taxBehavior;
    }

    public function setTaxBehavior(?TaxBehavior $taxBehavior): void
    {
        $this->taxBehavior = $taxBehavior;
    }

    public function getTiersMode(): ?PriceTiersMode
    {
        return $this->tiersMode;
    }

    public function setTiersMode(?PriceTiersMode $tiersMode): void
    {
        $this->tiersMode = $tiersMode;
    }

    public function getType(): ?PriceType
    {
        return $this->type;
    }

    public function setType(?PriceType $type): void
    {
        $this->type = $type;
    }

    public function getUnitAmount(): ?int
    {
        return $this->unitAmount;
    }

    public function setUnitAmount(?int $unitAmount): void
    {
        $this->unitAmount = $unitAmount;
    }

    public function getUnitAmountDecimal(): ?int
    {
        return $this->unitAmountDecimal;
    }

    public function setUnitAmountDecimal(?int $unitAmountDecimal): void
    {
        $this->unitAmountDecimal = $unitAmountDecimal;
    }
}
