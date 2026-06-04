<?php

namespace Xchert\Encapsulation;

use Xchert\Encapsulation\Exception\CannotUnsetException;
use Xchert\Encapsulation\Exception\NotAddableException;
use Xchert\Encapsulation\Exception\PropertyNotExistsException;
use Xchert\Util\Reflection;

abstract class PropertyEncapsulation extends AbstractEncapsulation
{
    use PropertyTrait;

    /**
     * @throws PropertyNotExistsException
     */
    public function set(string $field, mixed $value): void
    {
        $reflectionObject = new \ReflectionObject($this);

        $this->_set($field, $value, $reflectionObject);
    }

    /**
     * @throws PropertyNotExistsException
     */
    public function setList(array $data): void
    {
        $this->_setList($data);
    }

    /**
     * @throws PropertyNotExistsException
     * @throws CannotUnsetException
     */
    public function unset(string $field): void
    {
        $reflectionObject = new \ReflectionObject($this);
        $property = Reflection::getProperty($reflectionObject, $field);

        if($property === null) {
            throw new PropertyNotExistsException($this, $field);
        }

        if(!$property->isInitialized($this)) {
            return;
        }

        if($property->hasType() && !$property->getType()->allowsNull()) {
            throw new CannotUnsetException($this, $field);
        }

        $property->setValue($this, null);
    }

    /**
     * @throws NotAddableException
     * @throws PropertyNotExistsException
     */
    public function add(string $field, mixed $value): void
    {
        $reflectionObject = new \ReflectionObject($this);
        $adderMethod = Reflection::getMethod($reflectionObject, \sprintf('add%s', \ucfirst($field)));

        if($adderMethod !== null) {
            try {
                $adderMethod->invoke($this, $value);

                return;
            } catch(\ReflectionException $e) {
                // Do nothing. Will continue with property access
            }
        }

        $currentValue = $this->_get($field, $reflectionObject);
        $currentValue = $this->_add($currentValue, $value, $field);

        if(\is_array($currentValue)) {
            try {
                $this->_set($field, $currentValue, $reflectionObject);
            } catch(\Throwable $e) {
                throw NotAddableException::becauseOf($e, $field, $this);
            }
        }
    }

    /**
     * @throws NotAddableException
     * @throws PropertyNotExistsException
     */
    public function addList(string $field, array $values): void
    {
        $reflectionObject = new \ReflectionObject($this);

        $adderMethod = Reflection::getMethod($reflectionObject, \sprintf('add%s', \ucfirst($field)));

        if($adderMethod !== null) {
            try {
                foreach($values as $value) {
                    $adderMethod->invoke($this, $value);
                }

                return;
            } catch(\ReflectionException $e) {
                // Do nothing. Will continue with property access
            }
        }

        $currentValue = $this->_get($field, $reflectionObject);

        foreach($values as $value) {
            $currentValue = $this->_add($currentValue, $value, $field);
        }

        if(\is_array($currentValue)) {
            try {
                $this->_set($field, $currentValue, $reflectionObject);
            } catch(\Throwable $e) {
                throw NotAddableException::becauseOf($e, $field, $this);
            }
        }
    }

    /**
     * @throws NotAddableException
     */
    private function _add(mixed $currentValue, mixed $value, string $field): array|MutableContainer
    {
        if($currentValue === null) {
            $currentValue = $this->initializeCollection($field);
        }

        if(\is_array($currentValue)) {
            $currentValue[] = $value;

            return $currentValue;
        }

        if($currentValue instanceof MutableContainer) {
            try {
                $currentValue->add($value);
            } catch(\Throwable $e) {
                throw NotAddableException::becauseOf($e, $field, $this);
            }

            return $currentValue;
        }

        throw NotAddableException::notArrayOrContainer($field, \get_debug_type($currentValue), $this);
    }

    private function initializeCollection(string $field): array|MutableContainer
    {
        $property = Reflection::getProperty(new \ReflectionObject($this), $field);

        if($property === null) {
            return [];
        }

        if(!$property->hasType()) {
            return [];
        }

        $type = $property->getType();

        if(!$type instanceof \ReflectionNamedType) {
            return [];
        }

        $type = $type->getName();

        if($type === MutableContainer::class || \is_subclass_of($type, MutableContainer::class)) {
            $value = new $type();
            $property->setValue($this, $value);

            return $value;
        }

        return [];
    }
}
