<?php

namespace Xchert\Encapsulation;

interface Encapsulated extends \IteratorAggregate, \JsonSerializable
{
    public function get(string $field): mixed;

    public function getList(array $fields): array;

    public function has(string $field): bool;

    public function toArray(): array;

    public function getFields(): array;
}
