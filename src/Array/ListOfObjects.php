<?php

namespace AP\Validator\Array;

use AP\ErrorNode\Errors;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class ListOfObjects extends AbstractArray
{
    /**
     * @param string $class
     * @param string $message
     */
    public function __construct(
        public string $class,
        public string $message = "all elements must implement {class}",
    )
    {
    }

    final public function validateArray(array &$array): true|Errors
    {
        foreach ($array as $el) {
            if (!is_object($el) || !($el instanceof $this->class)) {
                return Errors::one($this->message, [
                    "class" => $this->class,
                ]);
            }
        }

        return true;
    }
}