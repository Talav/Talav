<?php

namespace Talav\Component\Resource\Error;

trait ErrorHandlerTrait
{
    protected function disableErrorHandler()
    {
        set_error_handler(function () {});
    }

    protected function restoreErrorHandler()
    {
        restore_error_handler();
    }
}