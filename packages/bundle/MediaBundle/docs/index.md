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
      provider: talav.media.provider.image
      formats:
        logo:
          w: 100
          h: 50
        small:
          w: 20
          h: 20
    article:
      provider: talav.media.provider.image
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
