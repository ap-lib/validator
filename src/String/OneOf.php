<?php

namespace AP\Validator\String;

use AP\ErrorNode\Errors;
use AP\OpenAPIPlus\OpenAPIModificator;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class OneOf extends AbstractString implements OpenAPIModificator
{
    public function __construct(
        public array  $options,
        public string $message = "must be one of: {options}'",
    )
    {
    }

    public function validateString(string &$str): true|Errors
    {
        if (!in_array($str, $this->options, true)) {
            return Errors::one($this->message, [
                "options" => implode(
                    ", ",
                    array_map(
                        fn($v) => var_export($v, true),
                        $this->options
                    )
                ),
            ]);
        }

        return true;
    }

    public function updateOpenAPIElement(array $spec): array
    {
        $spec['enum']    = $this->options;
        $spec['options'] = [];
        foreach ($this->options as $option) {
            $spec['options'][] = [
                'value' => $option,
                'label' => $option,
            ];
        }
        return $spec;
    }
}