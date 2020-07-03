TalavUserBundle
=============

The TalavUserBundle adds support for a database-backed user system in Symfony4+.
It provides a flexible framework for user management that aims to handle
common tasks such as user registration and password retrieval.

Features include:

- Users can be stored via Doctrine ORM
- Registration support, with an optional confirmation per email
- Password reset support
- Unit tested

Don't forget to register the bundles:

```
$bundles = [
    new AutoMapperPlus\AutoMapperPlusBundle\AutoMapperPlusBundle(),
    new Talav\ResourceBundle\TalavResourceBundle(),
    new Talav\UserBundle\TalavUserBundle(),
];
```

All available configuration options are listed below with their default values.

Configure auto mapper:
```
auto_mapper_plus:
    options:
        create_unregistered_mappings: true
```

Configure user bundle:
```code-block:: yaml
talav_user:
    user:
        class: Talav\UserBundle\Entity\User
        manager:
            class: Talav\UserBundle\Manager\UserManager
        factory:
            class: Talav\Component\Resource\Factory\Factory
    user_oauth:
        class: Talav\UserBundle\Entity\UserOAuth
        manager:
            class: Talav\UserBundle\Manager\UserOAuthManager
        factory:
            class: Talav\Component\Resource\Factory\Factory
    mailer:
        class: Talav\UserBundle\Mailer\UserMailer
    canonicalizer:
        class: Talav\Component\User\Canonicalizer\Canonicalizer
    password_updater:
        class: Talav\Component\User\Security\PasswordUpdater
    resetting:
        retry_ttl: 2 minutes
        token_ttl: 24 hours
    email:
        from:
            email:
            name:
```


