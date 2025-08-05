<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use AP\Validator\ValidatorOpenAPIInterface;
use Attribute;
use DateTimeImmutable;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class DateFormat extends AbstractString implements ValidatorOpenAPIInterface
{
    /**
     * @param string $format The expected date format.
     *                       See: https://www.php.net/manual/en/datetimeimmutable.createfromformat.php
     * @param string $message The error message if validation fails.
     *                        The placeholder '{format}' will be replaced with the expected format.
     */
    public function __construct(
        public string $format = 'Y-m-d',
        public string $message = "date does not match the required format '{format}'",
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        $dt = DateTimeImmutable::createFromFormat($this->format, $str);
        if ($dt === false || $dt->format($this->format) != $str) {
            return Errors::one($this->message, [
                "format" => $this->format,
            ]);
        }
        return true;
    }

    public function updateOpenAPIElement(array $spec): array
    {
        $spec['format'] = 'date';
        return $spec;
    }
}