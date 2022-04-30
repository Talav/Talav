Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require talav/media-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require talav/media-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Oneup\FlysystemBundle\OneupFlysystemBundle::class    => ['all' => true],
    Talav\MediaBundle\TalavMediaBundle:class             => ['all' => true]
];
```

Configuration
============

### Step 1: Install filesystem
Install required filesystems, follow [OneupFlysystemBundle setup](https://github.com/1up-lab/OneupFlysystemBundle/blob/main/doc/index.md)

### Step 2: Create entities
Symfony [suggests](https://symfony.com/doc/current/best_practices.html#use-attributes-to-define-the-doctrine-entity-mapping) to use PHP attributes to define the Doctrine Entity Mapping
Media entity:

```php

// src/Entity/Media.php

<?php

declare(strict_types=1);

namespace MediaAppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Talav\MediaBundle\Entity\Media as BaseMedia;

#[Entity]
#[Table(name: 'media')]
class Media extends BaseMedia
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue(strategy: 'AUTO')]
    protected $id = null;
}
```


### Step 2: Update `config/talav.yaml`
```yml 

talav_media:
  resources:
    media:
      classes:
        model: App\Entity\Media
```

Providers
=============
A provider class solves a simple use case: the management of a very specific
type of media.

A world document and an image file are two very different types of media and
each cannot be managed by a single class. In the ``TalavMediaBundle``, each is
represented by two provider classes: ``FileProvider`` and ``ImageProvider``.

A provider class is responsible for handling common things related to a media
asset:

* thumbnails
* path
* storing the media information (metadata)

A provider class is always linked to a ``Filesystem`` and a ``CDN``. The
filesystem abstraction uses the ``Flysystem`` library integrated using 
[OneupFlysystemBundle](https://github.com/1up-lab/OneupFlysystemBundle). The ``CDN``
abstraction is used to generate the media asset public URL.

By default, the filesystem and the CDN use the local filesystem and the current
server for the CDN.

In other words, when you create a provider, you don't need to worry about
how media assets are going to be store on the filesystem.


Media Context
=============

When a site has to handle pictures, you can have different type of pictures:
news pictures, users pictures etc. But in the end pictures require the same
features: resize, cdn and database relationship with entities.

The ``TalavMediaBundle`` tries to solve this situation by introducing ``context``:
a context has its own set of media providers and its own set of formats.
That means you can have a ``small`` user picture format and a ``small`` news
picture format with different sizes and providers.


Helpers
=============

The bundle comes with different helpers to render the thumbnail or the media
itself. The thumbnail always represents the media's image preview (i.e. the
thumbnail of a flash video). And the media helper generates the media itself
(i.e. the flash video).

Twig usage
----------

Render the reference to the original file:

```
    {{ media_reference(media) }}
```
Output similar to
```html
   /uploads/media/doc/0001/00/04054c62a7129bf967ba67c3b935ca18388d1781.txt
```

Render the reference to a thumbnail:

```txt
    {{ media_thumb_reference(media, 'small') }}
```

FileProvider does not provide any thumbnails. Output similar to

```html
   /uploads/media/avatar/0002/00/f7854c2624ec9581fd4b3c329c11ffc6.jpeg
```

Render the media:

```txt
    {{ media_thumb(media, 'small') }}

    {{ media_thumb(media, 'small', {'class': 'myclass'}) }}
```

FileProvider does not render any thumbnails. Output similar to

```html
<img title="avatar.jpeg" alt="avatar.jpeg" width="50" height="50" src="/uploads/media/avatar/0002/00/1d63d5d0226c54f6fd127d54d2eeb7891f77dceb.jpeg" >
```

Render the media:

```txt
    {{ media_media(media, 'small') }}

    {{ media_media(media, 'small', {'class': 'myclass'}) }}
```

The media helper for the ``talav.media.provider.image`` provider renders a responsive image tag with sensible defaults for ``srcset`` and ``sizes``.
The size configured will be the one used for the default fallback ``src``.

To override the ``sizes`` to fit your particular design, just pass a ``sizes`` option to the helper.

```txt
    {{ sonata_media(media, 'small', {'sizes': '(min-width: 20em) 50vw, 100vw'}) }}
```

To override the ``srcset`` attribute, just pass a ``srcset`` option to the
helper. The option expects either a string or an array of formats.

.. code-block:: jinja

    {{ sonata_media(media, 'large', {'srcset': ['small', 'big']}) }}

To render the image as ``<picture>`` element instead of ``<img>``, pass a ``picture`` key instead of ``srcset`` above:

.. code-block:: jinja

    {{ sonata_media(media, 'large', {'picture': ['small', 'big']}) }}

Media queries for ``<source>`` tags will default to a ``max-width`` equal to the image size.
If you need to specify media queries explicitly, do so with an object as follows:

.. code-block:: jinja

    {{ sonata_media(media, 'large', {'srcset': {'(max-width: 500px)': 'small', '(max-width: 1200px)': 'big'}}) }}

The format parameter (``'large'`` above) determines which size is going to be rendered as ``<img>`` inside the ``<picture>`` element.

Thumbnails for files
--------------------

The ``sonata.media.provider.file`` provider does not generate thumbnails.
This provider tries to display a default thumbnail.

The default thumbnail must be put in ``bundles/sonatamedia/file.png``.
It is automatically available there when you install assets using

.. code-block:: bash

    bin/console assets:install

Full configuration
=============

```yaml
talav_media:
  resources:
    media:
      classes:
        model: Media::class
        manager: MediaManager::class
        repository: ResourceRepository::class
  providers:
    file:
      service: talav.media.provider.file
      generator: talav.media.generator.uuid
      filesystem: oneup_flysystem.default_filesystem
      cdn: talav.media.cdn.server
      constrains: 
        extensions: [
          'pdf', 'txt', 'rtf','doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
          'odt', 'odg', 'odp', 'ods', 'odc', 'odf', 'odb', 'csv', 'xml'
        ]
        file_constraints:
          mimeTypes: [
             'application/pdf', 'application/x-pdf', 'application/rtf', 'text/html', 'text/rtf', 'text/plain',
             'application/excel', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint',
             'application/vnd.ms-powerpoint', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.graphics', 'application/vnd.oasis.opendocument.presentation', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.chart', 'application/vnd.oasis.opendocument.formula', 'application/vnd.oasis.opendocument.database', 'application/vnd.oasis.opendocument.image',
             'text/comma-separated-values', 'text/xml', 'application/xml', 'application/zip',
         ]
    image:
      service: talav.media.provider.image
      generator: talav.media.generator.uuid
      filesystem: oneup_flysystem.default_filesystem
      cdn: talav.media.cdn.server
      thumbnail: talav.media.thumbnail.glide
      constrains:
        extensions: ['jpg', 'png', 'jpeg']
        file_constraints:
          mimeTypes: ['image/pjpeg', 'image/jpeg', 'image/png', 'image/x-png']
        # both parameters are merged internally so image parameters override file parameters with the same key
        image_constraints:
          minWidth: 100
          minHeight: 100
          maxWidth: 3000
          maxHeight: 3000
      
  contexts:
    logo:
      providers: 
      - talav.media.provider.image
      formats:
        logo:
          w: 100
          h: 50
        small:
          w: 20
          h: 20
    article:
      providers: 
       - talav.media.provider.image
      formats:
        small:
          w: 100
          h: 50
        large:
          w: 1000
          h: 500
    doc:
      provider: talav.media.provider.file

  cdn:
    server:
      path: /upload/media
```
