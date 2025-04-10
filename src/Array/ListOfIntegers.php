<?php

namespace AP\Validator\Array;

use AP\ErrorNode\Errors;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class ListOfIntegers extends AbstractArray
{
    public function __construct(
        public string $message = "all elements must be integers",
    )
    {
    }

    final public function validateArray(array &$array): true|Errors
    {
        foreach ($array as $el) {
            if (!is_int($el)) {
                return Errors::one($this->message);
            }
        }

        return true;
    }
}