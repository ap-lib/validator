<?php

namespace AP\Validator\Int;

use AP\ErrorNode\Errors;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Between extends AbstractInt
{
    public function __construct(
        public ?int   $min = null,
        public ?int   $max = null,
        public string $message_between = "must be between '{min}' and '{max}'",
        public string $message_only_min = "must be equal or greater then '{min}'",
        public string $message_only_max = "must be equal or greater then '{max}'",
    )
    {
    }

    final public function validateInt(int &$int): true|Errors
    {
        if (is_int($this->min) && is_int($this->max) && ($int < $this->min || $int > $this->max)) {
            return Errors::one($this->message_between, [
                "min" => $this->min,
                "max" => $this->max,
            ]);
        }

        if (is_int($this->min) && $int < $this->min) {
            return Errors::one($this->message_only_min, [
                "min" => $this->min,
            ]);
        }

        if (is_int($this->max) && $int > $this->max) {
            return Errors::one($this->message_only_max, [
                "max" => $this->max,
            ]);
        }

        return true;
    }
}