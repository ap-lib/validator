<?php

namespace AP\Validator\Array;

use AP\ErrorNode\Errors;
use AP\Scheme\OpenAPI;
use AP\Validator\ValidatorOpenAPIInterface;
use Attribute;
use RuntimeException;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class ListOfObjects extends AbstractArray implements ValidatorOpenAPIInterface
{
    /**
     * @param string $class
     * @param string $message
     */
    public function __construct(
        public string $class,
        public string $message = "all elements must implement {class}",
    )
    {
    }

    final public function validateArray(array &$array): true|Errors
    {
        foreach ($array as $el) {
            if (!is_object($el) || !($el instanceof $this->class)) {
                return Errors::one($this->message, [
                    "class" => $this->class,
                ]);
            }
        }

        return true;
    }

    public function updateOpenAPIElement(array $spec): array
    {
        if (is_subclass_of($this->class, OpenAPI::class)) {
            throw new RuntimeException(
                '`%s` must implement `%s`',
                $this->class,
                OpenAPI::class
            );
        }
        $spec['type']       = 'array';
        $spec['properties'] = ($this->class)::openAPI();
        return $spec;
    }
}