<?php

namespace Xchert\Encapsulation;

use Xchert\Encapsulation\Exception\NotAddableException;

abstract class AbstractEncapsulation extends AbstractEncapsulated implements Encapsulation, \ArrayAccess
{
    final public function __construct(array $data = [])
    {
        $data = $this->initialize($data);

        $this->setList($data);
    }

    public function setList(array $data): void
    {
        foreach($data as $field => $value) {
            $this->set((string) $field, $value);
        }
    }

    /**
     * @throws NotAddableException
     */
    public function add(string $field, mixed $value): void
    {
        if(!$this->has($field)) {
            $this->set($field, [$value]);

            return;
        }

        $item = $this->get($field);

        if(\is_array($item)) {
            $item[] = $value;
            $this->set($field, $item);

            return;
        } elseif($item instanceof MutableContainer) {
            $item->add($value);

            return;
        }

        throw new NotAddableException($this, $field);
    }

    /**
     * @throws NotAddableException
     */
    public function addList(string $field, array $values): void
    {
        foreach($values as $value) {
            $this->add($field, $value);
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has((string) $offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get((string) $offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if(is_null($offset)) {
            throw new \RuntimeException('Cannot set a value to an Encapsulation without offset.');
        }

        $this->set((string) $offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->unset((string) $offset);
    }

    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }

    public function __unset(string $name): void
    {
        $this->unset($name);
    }

    protected function initialize(array $data): array
    {
        return $data;
    }
}
