<?php

namespace Xchert\Encapsulation;

class MutableContainer extends Container
{
    public function add(mixed ...$elements): self
    {
        return parent::add(...$elements);
    }

    public function clear(): self
    {
        $this->elements = [];

        return $this;
    }

    public function splice(int $offset, ?int $length = null, mixed $replacement = []): self
    {
        \array_splice($this->elements, $offset, $length, $replacement);

        return $this;
    }

    public function shift(): mixed
    {
        return \array_shift($this->elements);
    }

    public function unshift(mixed ...$elements): self
    {
        \array_unshift($this->elements, ...$elements);

        return $this;
    }

    public function pop(): mixed
    {
        return \array_pop($this->elements);
    }
}
