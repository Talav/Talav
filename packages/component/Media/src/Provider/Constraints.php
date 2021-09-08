<?php

declare(strict_types=1);

namespace Talav\Component\Media\Provider;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Constraints
{
    /** @var string[] */
    protected array $extensions = [];

    /** @var string[] */
    protected array $fileConstraints = [];

    /** @var string[] */
    protected array $imageConstraints = [];

    public function __construct(array $extensions, array $fileConstraints = [], array $imageConstraints = [])
    {
        $this->extensions = $extensions;
        $this->fileConstraints = $fileConstraints;
        $this->imageConstraints = $imageConstraints;
    }

    public function getFieldConstraints(): array
    {
        return [
            new Constraint\File($this->fileConstraints),
            new Constraint\Callback(
                function ($object, ExecutionContextInterface $context) {
                    if ($object instanceof UploadedFile) {
                        if (!$this->isValidExtension($object->getClientOriginalExtension())) {
                            $context->addViolation(
                                sprintf(
                                'It\'s not allowed to upload a file with extension "%s"',
                                $object->getClientOriginalExtension()
                            )
                            );
                        }
                    }
                }
            ),
        ];
    }

    /**
     * Validates provided extension.
     */
    protected function isValidExtension(string $ext): bool
    {
        return 0 == count($this->extensions) || in_array($ext, $this->extensions);
    }
}
