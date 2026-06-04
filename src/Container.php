<?php

namespace Xchert\Encapsulation;

class Container implements \Countable, \IteratorAggregate, \JsonSerializable
{
    public const ASCENDING = true;

    public const DESCENDING = false;

    protected array $elements = [];

    final public function __construct(array $elements = [])
    {
        $this->add(...$elements);
    }

    public static function merge(self ...$containers): self
    {
        $elements = [];

        foreach($containers as $container) {
            $elements = \array_merge($elements, $container->toArray());
        }

        return new static($elements);
    }

    public function toArray(): array
    {
        return \array_values($this->elements);
    }

    public function copy(): self
    {
        return new static($this->elements);
    }

    public function getAt(int $position): mixed
    {
        return \array_values($this->elements)[$position] ?? null;
    }

    public function count(): int
    {
        return \count($this->elements);
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function map(callable $callable): self
    {
        return new self(\array_map($callable, \array_values($this->elements)));
    }

    public function reduce(callable $callable, mixed $initial = null): mixed
    {
        return \array_reduce($this->elements, $callable, $initial);
    }

    public function filter(?callable $callable = null): self
    {
        return new static(\array_filter($this->elements, $callable));
    }

    public function slice(int $offset, ?int $length = null): self
    {
        return new static(\array_slice(\array_values($this->elements), $offset, $length));
    }

    public function unique(int $flags = SORT_STRING): self
    {
        return new static(\array_values(\array_unique($this->elements, $flags)));
    }

    public function reverse(): self
    {
        return new static(\array_reverse($this->elements));
    }

    public function search(mixed $needle, bool $strict = false): string|int|null
    {
        $result = \array_search($needle, \array_values($this->elements), $strict);

        return $result === false ? null : $result;
    }

    public function has(mixed $value): bool
    {
        return \in_array($value, $this->elements);
    }

    public function sort(?callable $callable = null, bool $direction = self::ASCENDING, int $flags = SORT_REGULAR): self
    {
        if($callable !== null) {
            \usort($this->elements, $callable);
        } elseif($direction) {
            \sort($this->elements, $flags);
        } else {
            \rsort($this->elements, $flags);
        }

        return $this;
    }

    public function chunk(int $length): array
    {
        return \array_map(function (array $chunk) {
            return new static($chunk);
        }, \array_chunk($this->elements, $length));
    }

    public function getIterator(): \Traversable
    {
        yield from $this->elements;
    }

    public function jsonSerialize(): mixed
    {
        return $this->elements;
    }

    protected function add(mixed ...$elements): self
    {
        foreach($elements as $element) {
            $this->validateType($element);
            $this->elements[] = $element;
        }

        return $this;
    }

    protected function getAllowedClass(): ?string
    {
        return null;
    }

    final protected function validateType(mixed $element): void
    {
        $class = $this->getAllowedClass();

        if($class === null) {
            return;
        }

        if(!$element instanceof $class) {
            throw new \InvalidArgumentException(\sprintf('%s can only hold %s. Got %s', \get_class($this), $class, \get_debug_type($element)));
        }
    }
}
