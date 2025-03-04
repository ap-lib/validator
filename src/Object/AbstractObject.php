<?php

namespace AP\Validator\Object;

use AP\ErrorNode\Errors;
use AP\Validator\ValidatorInterface;

abstract class AbstractObject implements ValidatorInterface
{
    /**
     * @param object $val
     * @return true|Errors
     */
    final public function validate(mixed &$val): true|Errors
    {
        return is_object($val)
            ? $this->validateObject($val)
            : Errors::one("must be a object");
    }

    abstract public function validateObject(object &$obj): true|Errors;
}