<?php

namespace AP\Validator\Int;

use AP\ErrorNode\Errors;
use AP\Validator\ValidateExceptionTrait;
use AP\Validator\ValidatorInterface;

abstract class AbstractInt implements ValidatorInterface
{
    use ValidateExceptionTrait;

    /**
     * @param object $val
     * @return true|Errors
     */
    final public function validate(mixed &$val): true|Errors
    {
        return is_int($val)
            ? $this->validateInt($val)
            : Errors::one("must be a float");
    }

    abstract public function validateInt(int &$int): true|Errors;
}