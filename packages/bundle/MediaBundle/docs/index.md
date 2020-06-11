Installation:

```
composer require talav/media-bundle
```

Update bundle configuration
```
<?php

return [
    // ...
    Oneup\FlysystemBundle\OneupFlysystemBundle::class    => ['all' => true],
    Liip\ImagineBundle\LiipImagineBundle::class          => ['all' => true],
    Talav\MediaBundle\TalavMediaBundle:class             => ['all' => true]
```

Install required filesystems, follow 
https://github.com/1up-lab/OneupFlysystemBundle/blob/master/Resources/doc/index.md

