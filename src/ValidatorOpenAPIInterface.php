<?php

namespace AP\Validator;

use AP\ErrorNode\Errors;

interface ValidatorOpenAPIInterface
{
    public function updateOpenAPIElement(array $spec): array;
}