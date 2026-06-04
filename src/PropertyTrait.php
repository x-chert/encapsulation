<?php

namespace Xchert\Encapsulation;

use Xchert\Encapsulation\Exception\PropertyNotExistsException;
use Xchert\Util\Reflection;
use Xchert\Util\Value;

trait PropertyTrait
{
    /**
     * @throws PropertyNotExistsException
     */
    public function get(string $field): mixed
    {
        $reflectionObject = new \ReflectionObject($this);

        return $this->_get($field, $reflectionObject);
    }

    /**
     * @throws PropertyNotExistsException
     */
    public function getList(array $fields): array
    {
        $reflectionObject = new \ReflectionObject($this);
        $result = [];

        /** @var string $field */
        foreach($fields as $field) {
            $result[$field] = $this->_get($field, $reflectionObject);
        }

        return $result;
    }

    public function has(string $field): bool
    {
        return Reflection::getProperty(new \ReflectionClass($this), $field) !== null;
    }

    public function toArray(): array
    {
        $reflectionObject = new \ReflectionObject($this);
        $result = [];

        foreach(Reflection::getProperties($reflectionObject) as $name => $property) {
            $result[$name] = $this->_get($name, $reflectionObject, $property);
        }

        return $result;
    }

    public function getFields(): array
    {
        return \array_keys(Reflection::getProperties(new \ReflectionClass($this)));
    }

    public function isEmpty(): bool
    {
        $reflectionObject = new \ReflectionObject($this);

        foreach(Reflection::getProperties($reflectionObject) as $name => $property) {
            $value = $this->_get($name, $reflectionObject, $property);

            if((is_array($value) && !empty($value)) || (!is_array($value) && $value !== null)) {
                return false;
            }
        }

        return true;
    }

    private function _setList(array $data): void
    {
        $reflectionObject = new \ReflectionObject($this);

        foreach($data as $field => $value) {
            $this->_set($field, $value, $reflectionObject);
        }
    }

    private function _set(string $field, mixed $value, \ReflectionObject $reflectionObject): void
    {
        if(Value::isEmpty($field)) {
            throw new PropertyNotExistsException($this, $field);
        }

        $setterMethod = Reflection::getMethod($reflectionObject, \sprintf('set%s', \ucfirst($field)));

        if($setterMethod !== null) {
            try {
                $setterMethod->invoke($this, $value);

                return;
            } catch(\ReflectionException $e) {
                // Do nothing. Will continue with property access
            }
        }

        $reflectionProperty = Reflection::getProperty($reflectionObject, $field);

        if($reflectionProperty === null) {
            throw new PropertyNotExistsException($this, $field);
        }

        $reflectionProperty->setValue($this, $value);
    }

    /**
     * @throws PropertyNotExistsException
     */
    private function _get(string $field, \ReflectionClass $reflectionObject, ?\ReflectionProperty $reflectionProperty = null): mixed
    {
        if(Value::isEmpty($field)) {
            throw new PropertyNotExistsException($this, $field);
        }

        $getterMethod = Reflection::getMethod($reflectionObject, \sprintf('get%s', \ucfirst($field)));

        if($getterMethod !== null) {
            try {
                return $getterMethod->invoke($this);
            } catch(\ReflectionException $e) {
                // Do nothing. Will continue with property access
            }
        }

        $reflectionProperty = $reflectionProperty ?? Reflection::getProperty($reflectionObject, $field);

        if($reflectionProperty === null) {
            throw new PropertyNotExistsException($this, $field);
        }

        if($reflectionProperty->hasType() && !$reflectionProperty->isInitialized($this)) {
            return null;
        }

        return $reflectionProperty->getValue($this);
    }
}
