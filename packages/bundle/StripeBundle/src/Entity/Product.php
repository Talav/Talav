<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;

class Product implements ResourceInterface, StripeObject, ProductInterface
{
    use ResourceTrait;
    use CreatedTrait;
    use UpdatedTrait;
    use ActiveTrait;
    use LivemodeTrait;
    use MetadataTrait;

    /**
     * The product’s description, meant to be displayable to the customer.
     * Use this field to optionally store a long form explanation of the product being sold for your own rendering purposes.
     */
    protected ?string $description = null;

    /**
     * A list of up to 8 URLs of images for this product, meant to be displayable to the customer.
     */
    protected array $images = [];

    /**
     * The product’s name, meant to be displayable to the customer.
     */
    protected ?string $name = null;

    /**
     * The dimensions of this product for shipping purposes.
     */
    protected array $packageDimensions = [];

    /**
     * Whether this product is shipped (i.e., physical goods).
     */
    protected bool $shippable = false;

    /**
     * Extra information about a product which will appear on your customer’s credit card statement.
     * In the case that multiple products are billed at once, the first statement descriptor will be used.
     */
    protected ?string $statementDescriptor = null;

    /**
     * A tax code ID.
     *
     * @see https://stripe.com/docs/tax/tax-categories
     */
    protected ?string $taxCode = null;

    /**
     * A label that represents units of this product in Stripe and on customers’ receipts and invoices. When set, this will be included in associated invoice line item descriptions.
     */
    protected ?string $unitLabel = null;

    /**
     * A URL of a publicly-accessible webpage for this product.
     */
    protected ?string $url = null;

    protected Collection $prices;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPackageDimensions(): array
    {
        return $this->packageDimensions;
    }

    public function setPackageDimensions(array $packageDimensions): void
    {
        $this->packageDimensions = $packageDimensions;
    }

    public function isShippable(): bool
    {
        return $this->shippable;
    }

    public function setShippable(bool $shippable): void
    {
        $this->shippable = $shippable;
    }

    public function getStatementDescriptor(): ?string
    {
        return $this->statementDescriptor;
    }

    public function setStatementDescriptor(?string $statementDescriptor): void
    {
        $this->statementDescriptor = $statementDescriptor;
    }

    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    public function setTaxCode(?string $taxCode): void
    {
        $this->taxCode = $taxCode;
    }

    public function getUnitLabel(): ?string
    {
        return $this->unitLabel;
    }

    public function setUnitLabel(?string $unitLabel): void
    {
        $this->unitLabel = $unitLabel;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function __toString(): string
    {
        if (is_null($this->getName()) || is_null($this->getId())) {
            return '';
        }

        return $this->getName().' ('.$this->getId().')';
    }
}
