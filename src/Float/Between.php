<?php

namespace AP\Validator\Float;

use AP\ErrorNode\Errors;
use AP\Validator\ValidatorOpenAPIInterface;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Between extends AbstractFloat implements ValidatorOpenAPIInterface
{
    public function __construct(
        public ?float $min = null,
        public ?float $max = null,
        public string $message_between = "must be between '{min}' and '{max}'",
        public string $message_only_min = "must be equal or greater then '{min}'",
        public string $message_only_max = "must be equal or less then '{max}'",
    )
    {
    }

    final public function validateFloat(float &$float): true|Errors
    {
        if (is_float($this->min) && is_float($this->max) && ($float < $this->min || $float > $this->max)) {
            return Errors::one($this->message_between, [
                "min" => $this->min,
                "max" => $this->max,
            ]);
        }

        if (is_float($this->min) && $float < $this->min) {
            return Errors::one($this->message_only_min, [
                "min" => $this->min,
            ]);
        }

        if (is_float($this->max) && $float > $this->max) {
            return Errors::one($this->message_only_max, [
                "max" => $this->max,
            ]);
        }

        return true;
    }

    public function updateOpenAPIElement(array $spec): array
    {
        if (is_int($this->min)) {
            $spec['minimum'] = $this->min;
        }
        if (is_int($this->max)) {
            $spec['maximum'] = $this->max;
        }
        return $spec;
    }
}