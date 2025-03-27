<?php

namespace AP\Validator;

use AP\ErrorNode\Errors;


trait ValidateExceptionTrait
{
    public function validateException(string &$str): void
    {
        $res = $this->validate($str);
        if ($res instanceof Errors) {
            throw $res->getNodeErrorsThrowable();
        }
    }
}