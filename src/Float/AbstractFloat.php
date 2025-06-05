<?php

namespace AP\Validator\Float;

use AP\ErrorNode\Errors;
use AP\Validator\ValidateExceptionTrait;
use AP\Validator\ValidatorInterface;

abstract class AbstractFloat implements ValidatorInterface
{
    use ValidateExceptionTrait;

    /**
     * @param object $val
     * @return true|Errors
     */
    final public function validate(mixed &$val): true|Errors
    {
        if (is_int($val)) {
            $val = (float)$val;
        }
        return is_float($val)
            ? $this->validateFloat($val)
            : Errors::one("must be a integer");
    }

    abstract public function validateFloat(float &$float): true|Errors;
}