<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use AP\Scheme\OpenAPIModificator;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class IP extends AbstractString implements OpenAPIModificator
{
    /**
     * Validates an IP address.
     *
     * @param string $message Error message displayed when validation fails.
     * @param int $options Validation options based on PHP's filter_var() flags.
     *                     Refer to: https://www.php.net/manual/en/filter.constants.php#constant.filter-validate-ip
     */
    public function __construct(
        public string $message = "value is not a valid IP address",
        public int    $options = 0
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        return filter_var($str, FILTER_VALIDATE_IP, $this->options)
            ? true
            : Errors::one($this->message);
    }

    public function updateOpenAPIElement(array $spec): array
    {
        $spec['format'] = 'ip';
        return $spec;
    }
}