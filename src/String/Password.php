<?php declare(strict_types=1);

namespace AP\Validator\String;

use AP\ErrorNode\Error;
use AP\ErrorNode\Errors;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Password extends AbstractString
{
    const int MAX_LENGTH_RECOMMENDED_FOR_BCRYPT = 72;

    public function __construct(
        public int    $min_length,
        public int    $max_length = self::MAX_LENGTH_RECOMMENDED_FOR_BCRYPT,
        public bool   $validate_spaces = true,
        public bool   $require_upper = true,
        public bool   $require_lower = true,
        public bool   $require_digit = true,
        public bool   $require_symbol = true,
        public string $message_length_min = "Must be at least '{min}' characters long",
        public string $message_length_max = "Must be no more than {max} bytes (Non-latin characters may count as more than one byte)",
        public string $message_start_end_whitespace = "Cannot start or end with whitespace",
        public string $message_require_upper = "Must contain at least one uppercase Latin letter",
        public string $message_require_lower = "Must contain at least one lowercase Latin letter",
        public string $message_require_digit = "Must contain at least one digit",
        public string $message_require_symbol = "Must contain at least one special character",
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        $errors = [];
        $len    = strlen($str);

        if ($len < $this->min_length) {
            $errors[] = new Error($this->message_length_min, context: ["min" => $this->min_length]);
        }

        if ($len > $this->max_length) {
            $errors[] = new Error($this->message_length_max, context: ["max" => $this->max_length]);
        }

        if ($this->validate_spaces && $str !== trim($str)) {
            $errors[] = new Error($this->message_start_end_whitespace);
        }

        if ($this->require_upper && !preg_match('/[A-Z]/', $str)) {
            $errors[] = new Error($this->message_require_upper);
        }

        if ($this->require_lower && !preg_match('/[a-z]/', $str)) {
            $errors[] = new Error($this->message_require_lower);
        }

        if ($this->require_digit && !preg_match('/[0-9]/', $str)) {
            $errors[] = new Error($this->message_require_digit);
        }

        if ($this->require_symbol && !preg_match('/[^a-zA-Z0-9]/', $str)) {
            $errors[] = new Error($this->message_require_symbol);
        }

        return empty($errors)
            ? true
            : new Errors($errors);
    }
}