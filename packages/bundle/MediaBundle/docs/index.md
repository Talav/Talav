Installation:

```
composer require talav/media-bundle
```

Update bundle configuration
```php
return [
    Oneup\FlysystemBundle\OneupFlysystemBundle::class    => ['all' => true],
    Talav\MediaBundle\TalavMediaBundle:class             => ['all' => true]
```

Install required filesystems, follow 
https://github.com/1up-lab/OneupFlysystemBundle/blob/master/Resources/doc/index.md

Full configuration:
```yaml
talav_media:
  resources:
    media:
      classes:
        model: Media::class
        manager: MediaManager::class
        repository: ResourceRepository::class
  providers:
    image:
      generator:  Prodly\Sonata\Media\Generator\DefaultGenerator
      filesystem:
      cdn:
      constrains:
        extensions: ['jpg', 'png', 'jpeg']
        mime_types: ['image/pjpeg', 'image/jpeg', 'image/png', 'image/x-png']
  contexts:
    logo:
      provider: sonata.media.provider.image
      presets:
        - logo
    icon:
      provider: sonata.media.provider.image
      presets:
        - icon
    post:
      provider: sonata.media.provider.image
      formats:
        - small
        - large
    doc:
      provider: sonata.media.provider.file

  cdn:
    server:
      path: /upload/media

  filesystem:
    local:
      # Directory for uploads should be writable
      directory: "%kernel.project_dir%/public/upload/media"
      create: false

  # if you don't use default namespace configuration
  class:
    media: Prodly\Entity\Media
    gallery: Prodly\Entity\Gallery
    gallery_has_media: Prodly\Entity\GalleryHasMedia
```
