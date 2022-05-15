<?php

namespace Talav\StripeBundle\AutoMapper;

use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\DataType;
use AutoMapperPlus\NameConverter\NamingConvention\CamelCaseNamingConvention;
use AutoMapperPlus\NameConverter\NamingConvention\SnakeCaseNamingConvention;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\StripeBundle\Entity\Customer;
use Talav\StripeBundle\Entity\Price;
use Talav\StripeBundle\Entity\PriceRecurring;
use Talav\StripeBundle\Entity\Product;
use Talav\StripeBundle\Entity\StripeObject;
use Talav\StripeBundle\Enum\BillingSchema;
use Talav\StripeBundle\Enum\PriceTiersMode;
use Talav\StripeBundle\Enum\PriceType;
use Talav\StripeBundle\Enum\RecurringAggregateUsage;
use Talav\StripeBundle\Enum\RecurringInterval;
use Talav\StripeBundle\Enum\RecurringUsageType;
use Talav\StripeBundle\Enum\TaxBehavior;
use Talav\StripeBundle\Enum\TaxExempt;
use Talav\StripeBundle\Message\Dto\ModelDto;

class MapperConfig implements AutoMapperConfiguratorInterface
{
    public function __construct(
        protected EntityManagerInterface $em,
        private readonly array $classMap
    ) {
    }

    public function configure(AutoMapperConfigInterface $config): void
    {
        // Internal DTO and entities use camel case, Stripe uses snake case
        $config->registerMapping(ModelDto::class, stdClass::class)
            ->withNamingConventions(
                new CamelCaseNamingConvention(),
                new SnakeCaseNamingConvention()
            );
        // This should cover simple cases that do not have any enums or embedded objects, e.g. Product
        $config->registerMapping(stdClass::class, StripeObject::class)
            ->withNamingConventions(
                new SnakeCaseNamingConvention(),
                new CamelCaseNamingConvention()
            );
        // Detailed mapping for Price
        $config->registerMapping(stdClass::class, Price::class)
            ->withNamingConventions(
                new SnakeCaseNamingConvention(),
                new CamelCaseNamingConvention()
            )
            ->forMember('billingScheme', function (stdClass $data) {
                return is_null($data->billing_scheme) ? null : BillingSchema::from($data->billing_scheme);
            })
            ->forMember('type', function (stdClass $data) {
                return is_null($data->type) ? null : PriceType::from($data->type);
            })
            ->forMember('tiersMode', function (stdClass $data) {
                return is_null($data->tiers_mode) ? null : PriceTiersMode::from($data->tiers_mode);
            })
            ->forMember('taxBehavior', function (stdClass $data) {
                return is_null($data->tax_behavior) ? null : TaxBehavior::from($data->tax_behavior);
            })
            ->forMember('recurring', function ($source, AutoMapperInterface $mapper, array $context): ?PriceRecurring {
                if (is_null($source->recurring)) {
                    return null;
                }
                if (!is_null($context[AutoMapper::DESTINATION_CONTEXT]->getRecurring())) {
                    return $mapper->mapToObject($source->recurring, $context[AutoMapper::DESTINATION_CONTEXT]->getRecurring());
                }

                return $mapper->map($source->recurring, PriceRecurring::class);
            })
            ->forMember('product', function (stdClass $data) {
                return $this->getProxyById($data->product, $this->classMap['product']);
            });
        $config->registerMapping(DataType::ARRAY, PriceRecurring::class)
            ->withNamingConventions(
                new SnakeCaseNamingConvention(),
                new CamelCaseNamingConvention()
            )
            ->forMember('interval', function (array $data) {
                return is_null($data['interval']) ? null : RecurringInterval::from($data['interval']);
            })
            ->forMember('aggregateUsage', function (array $data) {
                return is_null($data['aggregate_usage']) ? null : RecurringAggregateUsage::from($data['aggregate_usage']);
            })
            ->forMember('usageType', function (array $data) {
                return is_null($data['usage_type']) ? null : RecurringUsageType::from($data['usage_type']);
            });

        // Detailed mapping for Customer
        $config->registerMapping(stdClass::class, Customer::class)
            ->withNamingConventions(
                new SnakeCaseNamingConvention(),
                new CamelCaseNamingConvention()
            )
            ->forMember('taxExempt', function (stdClass $data) {
                return is_null($data->tax_exempt) ? null : TaxExempt::from($data->tax_exempt);
            });
    }

    protected function getProxyById($id, $entityName): ?ResourceInterface
    {
        return is_null($id) ? null : $this->em->getReference($entityName, $id);
    }
}
