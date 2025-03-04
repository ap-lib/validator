<?php

namespace AP\Validator\Array;

use AP\ErrorNode\Errors;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Count extends AbstractArray
{
    public function __construct(
        public ?int   $min = null,
        public ?int   $max = null,
        public string $message_between = "array count must be between '{min}' and '{max}'",
        public string $message_only_min = "array count must be equal or greater then '{min}'",
        public string $message_only_max = "array count must be equal or greater then '{max}'",
    )
    {
    }

    final public function validateArray(array &$array): true|Errors
    {
        $count = count($array);

        if (is_int($this->min) && is_int($this->max) && ($count < $this->min || $count > $this->max)) {
            return Errors::one($this->message_between, [
                "min" => $this->min,
                "max" => $this->max,
            ]);
        }

        if (is_int($this->min) && $count < $this->min) {
            return Errors::one($this->message_only_min, [
                "min" => $this->min,
            ]);
        }

        if (is_int($this->max) && $count > $this->max) {
            return Errors::one($this->message_only_max, [
                "max" => $this->max,
            ]);
        }

        return true;
    }
}