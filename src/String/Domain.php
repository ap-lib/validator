<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use AP\Validator\ValidatorOpenAPIInterface;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Domain extends AbstractString implements ValidatorOpenAPIInterface
{
    /**
     * Validates whether the domain name is valid according to » RFC 952, » RFC 1034, » RFC 1035, » RFC 1123,
     *                                                         » RFC 2732, and » RFC 2181
     *
     * @param string $message Error message displayed when validation fails.
     * @param int $options Validation options based on PHP's filter_var() flags.
     *                     Refer to: https://www.php.net/manual/en/filter.constants.php#constant.filter-validate-domain
     */
    public function __construct(
        public string $message = "value is not a valid domain name",
        public int    $options = 0
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        return filter_var($str, FILTER_VALIDATE_DOMAIN, $this->options)
            ? true
            : Errors::one($this->message);
    }

    public function updateOpenAPIElement(array $spec): array
    {
        $spec['format'] = 'hostname';
        return $spec;
    }
}