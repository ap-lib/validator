<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use AP\Validator\ValidateExceptionTrait;
use AP\Validator\ValidatorInterface;

abstract class AbstractString implements ValidatorInterface
{
    use ValidateExceptionTrait;

    /**
     * @param object $val
     * @return true|Errors
     */
    final public function validate(mixed &$val): true|Errors
    {
        return is_string($val)
            ? $this->validateString($val)
            : Errors::one("must be a string");
    }

    abstract public function validateString(string &$str): true|Errors;
}