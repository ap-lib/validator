<?php

namespace AP\Validator\Array;

use AP\ErrorNode\Errors;
use AP\Validator\ValidatorInterface;

abstract class AbstractArray implements ValidatorInterface
{
    /**
     * @param object $val
     * @return true|Errors
     */
    final public function validate(mixed &$val): true|Errors
    {
        return is_array($val)
            ? $this->validateArray($val)
            : Errors::one("must be an array");
    }

    abstract public function validateArray(array &$array): true|Errors;
}