<?php

namespace AP\Validator\Object;


use AP\Context\ContextForObjectInterface;
use AP\ErrorNode\Errors;
use AP\Scheme\Validation;
use AP\Validator\ValidatorInterface;
use Error;
use ReflectionClass;

class ObjectWithContextValidator extends AbstractObject
{
    /**
     * @param object $obj
     * @param array $path
     * @return true|Errors
     */
    public function validateObject(object &$obj, array $path = []): true|Errors
    {
        $context = $obj instanceof ContextForObjectInterface
            ? $obj->getContext()
            : null;

        $errors = [];

        $reflection = new ReflectionClass($obj);

        foreach ($reflection->getProperties() as $property) {
            // skip no public and no initialized
            if (!$property->isPublic() || !$property->isInitialized($obj)) {
                continue;
            }

            // skip null if allow null
            $name = $property->getName();
            if ($property->getType()->allowsNull() && is_null($obj->$name)) {
                continue;
            }

            // element implement Validation scheme
            if ($obj->$name instanceof Validation) {
                if (!is_null($context) && $obj->$name instanceof ContextForObjectInterface) {
                    $obj->$name->setContext($context);
                }

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
                        if (!is_null($context) && $validator instanceof ContextForObjectInterface) {
                            $validator->setContext($context);
                        }
                        try {
                            $validateRes = $validator->validate($obj->$name);
                        } catch (Error $e) {
                            if (str_contains($e->getMessage(), "readonly property")) {
                                $v1          = $v2 = $obj->$name;
                                $validateRes = $validator->validate($v1);
                                if ($v1 != $v2) {
                                    throw $e;
                                }
                            } else {
                                throw $e;
                            }
                        }
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