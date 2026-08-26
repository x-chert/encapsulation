<?php

namespace Xchert\Encapsulation;

interface Encapsulated extends \IteratorAggregate, \JsonSerializable
{
    public function get(string $field): mixed;

    /** @return array<string, mixed> */
    public function getList(array $fields): array;

    public function has(string $field): bool;

    /** @return array<string, mixed> */
    public function toArray(): array;

    /** @return string[] */
    public function getFields(): array;
}
