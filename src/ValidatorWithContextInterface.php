<?php

namespace AP\Validator;

use AP\Context\Context;

interface ValidatorWithContextInterface
{
    public function setContext(Context $context): void;

    public function getContext(): ?Context;
}