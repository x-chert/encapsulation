<?php

namespace Xchert\Encapsulation;

use Xchert\Encapsulation\Exception\NotAllowedFieldException;

trait ArrayTrait
{
    private array $data = [];

    public function get(string $field): mixed
    {
        return $this->data[$field] ?? null;
    }

    public function has(string $field): bool
    {
        return array_key_exists($field, $this->data);
    }

    public function getFields(): array
    {
        return array_keys($this->data);
    }

    public function isFieldAllowed(string $field): bool
    {
        return true;
    }

    /**
     * @throws NotAllowedFieldException
     */
    private function _set(string $field, mixed $value): void
    {
        if(!$this->isFieldAllowed($field)) {
            throw new NotAllowedFieldException($field, $this);
        }

        $this->data[$field] = $value;
    }
}
