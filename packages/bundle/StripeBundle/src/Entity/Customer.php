<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\StripeBundle\Enum\TaxExempt;

class Customer implements ResourceInterface, StripeObject, CustomerInterface
{
    use ResourceTrait;
    use CreatedTrait;
    use UpdatedTrait;
    use LivemodeTrait;
    use MetadataTrait;
    use DescriptionTrait;
    use NameTrait;

    /**
     * Current balance, if any, being stored on the customer. If negative, the customer has credit to apply to their next invoice.
     * If positive, the customer has an amount owed that will be added to their next invoice.
     * The balance does not refer to any unpaid invoices; it solely takes into account amounts that have yet to be successfully applied to any invoice.
     * This balance is only taken into account as invoices are finalized.
     */
    protected int $balance = 0;

    /**
     * Three-letter ISO code for the currency the customer can be charged in for recurring billing purposes.
     */
    protected ?string $currency = null;

    /**
     * ID of the default payment source for the customer.
     * NOT MAPPED.
     */
    protected mixed $defaultSource = null;

    /**
     * When the customer’s latest invoice is billed by charging automatically, delinquent is true if the invoice’s latest charge failed.
     * When the customer’s latest invoice is billed by sending an invoice, delinquent is true if the invoice isn’t paid by its due date.
     */
    protected bool $delinquent = false;

    /**
     * Describes the current discount active on the customer, if there is one.
     * NOT MAPPED.
     */
    protected mixed $discount = null;

    /**
     * The customer’s email address.
     */
    protected ?string $email = null;

    /**
     * The prefix for the customer used to generate unique invoice numbers.
     */
    protected ?string $invoicePrefix = null;

    /**
     * The customer’s default invoice settings.
     * NOT MAPPED.
     */
    protected mixed $invoiceSettings = null;

    /**
     * The suffix of the customer’s next invoice number, e.g., 0001.
     */
    protected int $nextInvoiceSequence = 1;

    /**
     * The customer’s phone number.
     */
    protected ?string $phone = null;

    /**
     * The customer’s preferred locales (languages), ordered by preference.
     */
    protected array $preferredLocales = [];

    /**
     * Mailing and shipping address for the customer. Appears on invoices emailed to this customer.
     * NOT MAPPED.
     */
    protected mixed $shipping = null;

    /**
     * Describes the customer’s tax exemption status. One of none, exempt, or reverse.
     * When set to reverse, invoice and receipt PDFs include the text “Reverse charge”.
     */
    protected TaxExempt $taxExempt = TaxExempt::NONE;

    /**
     * ID of the test clock this customer belongs to.
     * NOT MAPPED.
     */
    protected ?string $testClock = null;

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getDefaultSource(): mixed
    {
        return $this->defaultSource;
    }

    public function setDefaultSource(mixed $defaultSource): void
    {
        $this->defaultSource = $defaultSource;
    }

    public function isDelinquent(): bool
    {
        return $this->delinquent;
    }

    public function setDelinquent(bool $delinquent): void
    {
        $this->delinquent = $delinquent;
    }

    public function getDiscount(): mixed
    {
        return $this->discount;
    }

    public function setDiscount(mixed $discount): void
    {
        $this->discount = $discount;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getInvoicePrefix(): ?string
    {
        return $this->invoicePrefix;
    }

    public function setInvoicePrefix(?string $invoicePrefix): void
    {
        $this->invoicePrefix = $invoicePrefix;
    }

    public function getInvoiceSettings(): mixed
    {
        return $this->invoiceSettings;
    }

    public function setInvoiceSettings(mixed $invoiceSettings): void
    {
        $this->invoiceSettings = $invoiceSettings;
    }

    public function getNextInvoiceSequence(): int
    {
        return $this->nextInvoiceSequence;
    }

    public function setNextInvoiceSequence(int $nextInvoiceSequence): void
    {
        $this->nextInvoiceSequence = $nextInvoiceSequence;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getPreferredLocales(): array
    {
        return $this->preferredLocales;
    }

    public function setPreferredLocales(array $preferredLocales): void
    {
        $this->preferredLocales = $preferredLocales;
    }

    public function getShipping(): mixed
    {
        return $this->shipping;
    }

    public function setShipping(mixed $shipping): void
    {
        $this->shipping = $shipping;
    }

    public function getTaxExempt(): TaxExempt
    {
        return $this->taxExempt;
    }

    public function setTaxExempt(TaxExempt $taxExempt): void
    {
        $this->taxExempt = $taxExempt;
    }

    public function getTestClock(): ?string
    {
        return $this->testClock;
    }

    public function setTestClock(?string $testClock): void
    {
        $this->testClock = $testClock;
    }
}
