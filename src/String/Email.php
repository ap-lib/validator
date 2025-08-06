<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use AP\OpenAPIPlus\OpenAPIModificator;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Email extends AbstractString implements OpenAPIModificator
{
    /**
     * Validates whether the value is a "valid" e-mail address.
     * The validation is performed against the addr-spec syntax in » RFC 822.
     * However, comments, whitespace folding, and dotless domain names aren't supported, and thus will be rejected.
     *
     * @param string $message Error message displayed when validation fails.
     * @param int $options Validation options based on PHP's filter_var() flags.
     *                     Refer to: https://www.php.net/manual/en/filter.constants.php#constant.filter-validate-email
     */
    public function __construct(
        public string $message = "value is not a valid e-mail address",
        public int    $options = 0
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        return filter_var($str, FILTER_VALIDATE_EMAIL, $this->options)
            ? true
            : Errors::one($this->message);
    }

    public function updateOpenAPIElement(array $spec): array
    {
        $spec['format'] = 'email';
        return $spec;
    }
}