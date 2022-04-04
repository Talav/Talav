Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require talav/user-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require talav/user-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Talav\UserBundle\TalavUserBundle::class => ['all' => true],
];
```

Configuration
============

### Step 1: Create entities
Symfony [suggests](https://symfony.com/doc/current/best_practices.html#use-attributes-to-define-the-doctrine-entity-mapping) to use PHP attributes to define the Doctrine Entity Mapping
User entity:

```php

// src/Entity/User.php

<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Talav\UserBundle\Entity\User as BaseUser;

#[Entity]
#[Table(name: "user")]
class User extends BaseUser
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue(strategy: "AUTO")]
    protected $id = null;
}
```

User entity:

```php

// src/Entity/UserOAuth.php

<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Talav\UserBundle\Entity\UserOAuth as BaseUserOAuth;

#[Entity]
#[Table(name: "user_oauth")]
class UserOAuth extends BaseUserOAuth
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue(strategy: "AUTO")]
    protected $id = null;
}

```
### Step 2: Minimum bundle configuration for `config/talav.yaml`
```yml 

talav_user:
  resources:
    user:
      classes:
        model: App\Entity\User
    user_oauth:
      classes:
        model: App\Entity\UserOAuth
  email:
    from:
      email: test@test.com
      name: Tester
```

### Step 3: Update `security.yaml`
```yml 

security:
    password_hashers:
        App\Entity\User:
            algorithm: auto

    providers:
        app_user_provider:
            id: talav.user.provider.oath
```

### Step 4: Remove auto generated classes
`src/Securiry/User.php`
`src/Securiry/UserProvider.php`