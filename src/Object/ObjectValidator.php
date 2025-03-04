<?php

namespace AP\Validator\Object;


use AP\ErrorNode\Errors;
use AP\Validator\ValidatorInterface;
use ReflectionClass;
use ReflectionException;

class ObjectValidator extends AbstractObject
{
    /**
     * @param object $value
     * @param array $path
     * @return true|Errors
     * @throws ReflectionException
     */
    final public function validateObject(mixed &$obj, array $path = []): true|Errors
    {
        $errors = [];

        $reflection = new ReflectionClass($obj);

        foreach ($reflection->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                if (!$property->isPublic()) {
                    continue;
                }
                $name = $property->getName();
                if ($property->getType()->allowsNull() && is_null($obj->$name)) {
                    continue;
                }

                if (is_subclass_of($attribute->getName(), ValidatorInterface::class)) {
                    $validator = $attribute->newInstance();
                    if ($validator instanceof ValidatorInterface) {
                        $validateRes = $validator->validate($obj->$name);
                        if ($validateRes instanceof Errors) {
                            foreach ($validateRes->getErrors() as $error) {
                                $error->path = array_merge($path, [$name], $error->path);
                                $errors[]    = $error;
                            }
                        }
                    }
                }
            }
        }


        return empty($errors)
            ? true
            : new Errors($errors);
    }
}