<?php

namespace Xchert\Encapsulation;

interface Encapsulation extends Encapsulated
{
    public function set(string $field, mixed $value): void;

    /** @param array<string, mixed> $data */
    public function setList(array $data): void;

    public function unset(string $field): void;

    public function add(string $field, mixed $value): void;

    /** @param array<int, mixed> $values */
    public function addList(string $field, array $values): void;
}
