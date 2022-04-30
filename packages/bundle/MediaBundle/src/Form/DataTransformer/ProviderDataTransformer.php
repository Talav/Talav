<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Talav\MediaBundle\Form\Model\MediaModel;

class ProviderDataTransformer implements DataTransformerInterface
{
    protected array $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $this->getOptions($options);
    }

    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($model)
    {
        if (!$model instanceof MediaModel) {
            return $model;
        }

        if (!$model->provider && $this->options['provider']) {
            $model->provider = $this->options['provider'];
        }

        if (!$model->context && $this->options['context']) {
            $model->context = $this->options['context'];
        }

        if ($this->options['delete_on_unlink'] && $model->unlink) {
            $model->delete = true;
        }

        return $model;
    }

    protected function getOptions(array $options): array
    {
        return array_merge([
            'provider' => false,
            'context' => false,
        ], $options);
    }
}
