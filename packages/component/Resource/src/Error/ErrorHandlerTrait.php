<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Error;

trait ErrorHandlerTrait
{
    protected function disableErrorHandler(): void
    {
        set_error_handler(function () {});
    }

    protected function restoreErrorHandler(): void
    {
        restore_error_handler();
    }
}
