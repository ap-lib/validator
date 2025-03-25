<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Regexp extends AbstractString
{
    /**
     * @param string $pattern The regular expression pattern to validate the input
     * @param string $message The error message if validation fails
     * @param bool $pattern_to_context Whether to include the regular expression pattern in the error context
     */
    public function __construct(
        public string $pattern,
        public string $message = "value does not match the expected format",
        public bool   $pattern_to_context = false,
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        if (preg_match($this->pattern, $str)) {
            return true;
        }
        return Errors::one(
            $this->message,
            $this->pattern_to_context
                ? ["pattern" => $this->pattern]
                : []
        );
    }
}