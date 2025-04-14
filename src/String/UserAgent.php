<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class UserAgent extends AbstractString
{
    /**
     * Validates a user agent string.
     *
     * @param string $message Error message displayed when validation fails.
     * @param int $min_length Minimum allowed length for the user agent string.
     * @param int $max_length Maximum allowed length for the user agent string.
     */
    public function __construct(
        public string $message = "value is not a valid User-Agent",
        public int    $min_length = 0,
        public int    $max_length = 1000
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        $length = strlen($str);

        if ($length < $this->min_length || $length > $this->max_length) {
            return Errors::one($this->message);
        }

        // Allow visible printable characters only (ASCII range 32–126)
        if (!preg_match('/^[\x20-\x7E]+$/', $str)) {
            return Errors::one($this->message);
        }

        return true;
    }
}