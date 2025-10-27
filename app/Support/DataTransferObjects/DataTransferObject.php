<?php

namespace Support\DataTransferObjects;

use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{
    public function __construct(array $data = [])
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            
            if (!array_key_exists($propertyName, $data)) {
                continue;
            }

            $this->{$propertyName} = $data[$propertyName];
        }
    }

    public function toArray(): array
    {
        $class = new ReflectionClass(static::class);
        $data = [];

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            $data[$propertyName] = $this->{$propertyName};
        }

        return $data;
    }
}