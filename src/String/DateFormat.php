<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use Attribute;
use DateTimeImmutable;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class DateFormat extends AbstractString
{
    /**
     * @param int|null $format The expected date format.
     *                         See: https://www.php.net/manual/en/datetimeimmutable.createfromformat.php
     * @param string $message The error message if validation fails.
     *                        The placeholder '{format}' will be replaced with the expected format.
     */
    public function __construct(
        public ?int   $format = null,
        public string $message = "date does not match the required format '{format}'",
    )
    {
    }

    final public function validateString(string &$str): true|Errors
    {
        if (DateTimeImmutable::createFromFormat($this->format, $str) === false) {
            return Errors::one($this->message, [
                "format" => $this->format,
            ]);
        }
        return true;
    }
}