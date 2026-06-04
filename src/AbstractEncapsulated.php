<?php

namespace Xchert\Encapsulation;

abstract class AbstractEncapsulated implements Encapsulated
{
    abstract public function __construct(array $data = []);

    public function getList(array $fields): array
    {
        $result = [];

        foreach($fields as $fieldName) {
            $result[$fieldName] = $this->get($fieldName);
        }

        return $result;
    }

    public function toArray(): array
    {
        return $this->getList($this->getFields());
    }

    public function isEmpty(): bool
    {
        return empty($this->toArray());
    }

    public function getIterator(): \Traversable
    {
        yield from $this->toArray();
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function __get(string $name)
    {
        return $this->get($name);
    }

    public function __isset(string $name): bool
    {
        return $this->has($name);
    }
}
