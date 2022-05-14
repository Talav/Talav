Subscription component for Talav framework
=============

Concepts
============
The majority of concepts comes from Stripe API. 

Products
-
Products describe the specific services you offer to your customers. For example, you might offer a Standard and Premium version of your service; each version would be a separate Product.

Prices
- 
Prices define the unit cost, currency, and billing cycle for recurring purchases of products. 
Products help you track provisioning, and prices help you track payment terms. 
Different levels of service should be represented by products, and pricing options should be represented by prices. 
This approach lets you change prices without having to change your provisioning scheme.

For example, you might have a single "gold" product that has prices for $10/month, $100/year, and €9 once.

Subscriptions
-
With Subscriptions, customers make recurring payments for access to a product. 
Subscriptions require you to retain more information about your customers than one-time purchases do because you need to charge customers in the future.

Customers
-
This object represents a customer of your business. It lets you create recurring charges and track payments that belong to the same customer.

Features
-
Features describe differences between products that can be checked programmatically. 
For example if you sell deployment services one of produce features might be a number of services included.

```php

$product1 = new Product('Free Plan');
$product1->addFeature(new Feature('NUMBER_SERVICES', false));

$product2 = new Product('Silver Plan');
$product2->addFeature(new Feature('NUMBER_SERVICES', true, false, 5));

$product3 = new Product('Gold Plan');
$product3->addFeature(new Feature('NUMBER_SERVICES', true, true));

```