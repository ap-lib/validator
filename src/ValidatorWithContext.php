<?php

namespace AP\Validator;

use AP\Context\Context;

trait ValidatorWithContext
{
    protected ?Context $_context = null;

    public function setContext(Context $context): void
    {
        $this->_context = $context;
    }

    public function getContext(): ?Context
    {
        return $this->_context;
    }
}