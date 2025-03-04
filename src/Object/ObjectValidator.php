<?php

namespace AP\Validator\Object;


use AP\ErrorNode\Errors;
use AP\Scheme\Validation;
use AP\Validator\ValidatorInterface;
use ReflectionClass;

class ObjectValidator extends AbstractObject
{
    /**
     * @param object $obj
     * @param array $path
     * @return true|Errors
     */
    final public function validateObject(object &$obj, array $path = []): true|Errors
    {
        $errors = [];

        $reflection = new ReflectionClass($obj);

        foreach ($reflection->getProperties() as $property) {
            // skip no public
            if (!$property->isPublic()) {
                continue;
            }

            // skip null if allow null
            $name = $property->getName();
            if ($property->getType()->allowsNull() && is_null($obj->$name)) {
                continue;
            }

            // element implement Validation scheme
            if ($obj->$name instanceof Validation) {
                $validateRes = $obj->$name->isValid($obj->$name);
                if ($validateRes instanceof Errors) {
                    foreach ($validateRes->getErrors() as $error) {
                        $error->path = array_merge($path, [$name], $error->path);
                        $errors[]    = $error;
                    }
                }
            }

            // validation attributes
            foreach ($property->getAttributes() as $attribute) {
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