<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use AP\Scheme\OpenAPIModificator;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class UserAgent extends AbstractString implements OpenAPIModificator
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
        public int    $min_length = 1,
        public int    $max_length = 1000,
        public string $spec_description = 'Standard HTTP User-Agent string',
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
        if (!preg_match('/^[\x20-\x7E]*$/', $str)) {
            return Errors::one($this->message);
        }

        return true;
    }

    public function updateOpenAPIElement(array $spec): array
    {
        $spec['description'] = $this->spec_description;
        $spec['minLength']   = $this->max_length;
        $spec['maxLength']   = $this->max_length;
        return $spec;
    }
}