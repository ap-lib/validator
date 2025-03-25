<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Url extends AbstractString
{
    /**
     * Validates whether the URL name is valid according to » RFC 2396
     *
     * @param string $message Error message displayed when validation fails.
     * @param int $options Validation options based on PHP's filter_var() flags.
     *                     Refer to: https://www.php.net/manual/en/filter.constants.php#constant.filter-validate-url
     */
    public function __construct(
        public string $message = "value is not a valid URL name",
        public int    $options = 0
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        return filter_var($str, FILTER_VALIDATE_URL, $this->options)
            ? true
            : Errors::one($this->message);
    }
}