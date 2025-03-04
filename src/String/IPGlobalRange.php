<?php

namespace AP\Validator\String;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class IPGlobalRange extends IP
{
    /**
     * Validates an IP address that belongs to the global range.
     *
     * @param string $message Error message displayed when validation fails.
     * @param int $options Additional validation options based on PHP's filter_var() flags
     *        `FILTER_FLAG_GLOBAL_RANGE` is always applied
     *        Other options: @see https://www.php.net/manual/en/filter.constants.php#constant.filter-validate-ip
     */
    public function __construct(
        string $message = "value is not a valid globally routable IP address",
        int    $options = 0
    )
    {
        parent::__construct(
            $message,
            $options | FILTER_FLAG_GLOBAL_RANGE
        );
    }
}