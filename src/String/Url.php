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
     * @param ?array $stricter_schemes List of allowed schemes, for example ['http', 'https'], lowercase only.
     */
    public function __construct(
        public string $message = "value is not a valid URL name",
        public int    $options = 0,
        public ?array $stricter_schemes = null,
        public string $message_scheme = "scheme must be one of: {schemes}",
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        if (!filter_var($str, FILTER_VALIDATE_URL, $this->options)) {
            return Errors::one($this->message);
        }

        if ($this->stricter_schemes !== null) {
            $scheme = parse_url($str, PHP_URL_SCHEME);
            if (!in_array(strtolower((string)$scheme), $this->stricter_schemes, true)) {
                return Errors::one($this->message_scheme, [
                    "schemes" => implode(", ", $this->stricter_schemes)
                ]);
            }
        }
        return true;
    }
}