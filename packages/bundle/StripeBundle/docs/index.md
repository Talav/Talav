TalavStripeBundle
====================

The TalavStripeBundle integrates Stripe PHP SDK to your Symfony project.
Also you can configure bundle to save Stripe data in database.
You are free to choose what Stripe objects will be stored.

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require talav/stripe-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require talav/stripe-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Talav\StripeBundle\TalavStripeBundle:class             => ['all' => true]
];
```

### Step 3: Config bundle
```yaml
# Allow messages to have no handlers
framework:
  messenger:
    buses:
      event.bus:
        default_middleware: allow_no_handlers

# Required settings for automapper
auto_mapper_plus:
  options:
    create_unregistered_mappings: true
    ignore_null_properties: true

# Configure resources for bundle
talav_stripe:
  secret_key: "test"
  webhook_secret:
  resources:
    product:
      classes:
        model: <created entity from your app>
```