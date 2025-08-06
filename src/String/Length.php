<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use AP\OpenAPIPlus\OpenAPIModificator;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Length extends AbstractString implements OpenAPIModificator
{
    public function __construct(
        public ?int    $min = null,
        public ?int    $max = null,
        public bool    $mb = true,
        public ?string $mb_encoding = null,
        public string  $message_between = "length must be between '{min}' and '{max}'",
        public string  $message_only_min = "length must be equal or greater then '{min}'",
        public string  $message_only_max = "length must be equal or less then '{max}'",
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        $len = $this->mb
            ? mb_strlen($str, $this->mb_encoding)
            : strlen($str);

        if (is_int($this->min) && is_int($this->max) && ($len < $this->min || $len > $this->max)) {
            return Errors::one($this->message_between, [
                "min" => $this->min,
                "max" => $this->max,
            ]);
        }

        if (is_int($this->min) && $len < $this->min) {
            return Errors::one($this->message_only_min, [
                "min" => $this->min,
            ]);
        }

        if (is_int($this->max) && $len > $this->max) {
            return Errors::one($this->message_only_max, [
                "max" => $this->max,
            ]);
        }

        return true;
    }

    public function updateOpenAPIElement(array $spec): array
    {
        if (is_int($this->min)) {
            $spec['minLength'] = $this->min;
        }
        if (is_int($this->max)) {
            $spec['maxLength'] = $this->max;
        }
        return $spec;
    }
}