<?php

namespace AP\Validator;

use AP\ErrorNode\Errors;

interface ValidatorInterface
{
    public function validate(mixed &$val): true|Errors;
}