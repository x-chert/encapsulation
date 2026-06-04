<?php

namespace Xchert\Encapsulation;

interface Encapsulation extends Encapsulated
{
    public function set(string $field, mixed $value): void;

    public function setList(array $data): void;

    public function unset(string $field): void;

    public function add(string $field, mixed $value): void;

    public function addList(string $field, array $values): void;
}
