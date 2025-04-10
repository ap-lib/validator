<?php

namespace AP\Validator;

use AP\ErrorNode\Errors;


trait ValidateExceptionTrait
{
    public function validateException(mixed &$value): void
    {
        $res = $this->validate($value);
        if ($res instanceof Errors) {
            throw $res->getNodeErrorsThrowable();
        }
    }
}